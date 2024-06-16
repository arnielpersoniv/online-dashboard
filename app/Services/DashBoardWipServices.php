<?php

namespace App\Services;

use App\Models\OpenInfraTask;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashBoardWipServices
{

    public $model;
    public $user;

    public function __construct()
    {
        $this->model = new OpenInfraTask();
        $this->user = new User();
    }

    //filter data
    public function loadAll($where)
    {
        $users = $this->user->where('role','agent')->whereNull('deleted_at')->get();
        $query = DB::table('open_infra_tasks as a')
            ->join('users as b', function ($join) {
                $join->on('a.agent_id', '=', 'b.id');
            })
            ->select('task', 'name as releasedby', DB::raw('DATE_FORMAT(a.created_at, "%Y-%m-%d") as datecreated'),DB::raw('COUNT(task) as total'))
            //->where('status', '<>', 'completed')
            ->whereNull('a.deleted_at')
            ->groupBy('task','name','a.created_at');

        if ($where['filter'] == 'daily') {
            $datelabel = date("F j, Y", strtotime($where['date']));
            $querries = $query->whereDate('a.created_at', $where['date']);
            $results = $querries->get();
            $userdata = $this->getAgentData($users, $results, $datelabel);
            $datatask = $this->getAllTask($results, $datelabel);
            $usertask = $this->getAgentTaskData($users, $results, $datelabel);
            $alltask  = $this->getAllTask($results, $datelabel);
        } 
        elseif ($where['filter'] == 'weekly') {
            $datelabel = date("F", strtotime($where["date"]));
            $start = CarbonImmutable::parse($where['date']);
            $end = $start->addDays(6);
            $dateweeklabel = date("F", strtotime($where['date'])) . " " . date("d", strtotime($start)) . "-" . date("d", strtotime($end)) . ", " . date("Y", strtotime($where['date']));
            $querries = $query->whereBetween('a.created_at', [$start, $end]);
            $results = $querries->get();
            $userdata = $this->weeklyAgent($users, $results, $datelabel);
            $datatask = $this->getWeeklyTask($results, $datelabel);
            $usertask = $this->getAgentTaskData($users, $results, $dateweeklabel);
            $alltask = $this->getTaskWeeklyData($results, $where["date"]);
        } 
        elseif ($where['filter'] == 'monthly') {
            $datelabel = date("F", strtotime($where["date"]));
            $month = explode("-", $where["date"]);
            $querries = $query->whereMonth('a.created_at', $month[1]);
            $results = $querries->get();
            $userdata = $this->getAgentData($users, $results, $datelabel);
            $datatask = $this->getAllTask($results, $datelabel);
            $usertask = $this->getAgentTaskData($users, $results, $datelabel);
            $alltask = $this->getAllTask($results, $datelabel);
        } 
        elseif ($where['filter'] == 'yearly') {
            $datelabel = date("Y", strtotime($where["date"]));
            $querries = $query->whereYear('a.created_at', $where["date"]);
            $results = $querries->get();
            $userdata = $this->getAgentData($users, $results, $datelabel);
            $datatask = $this->getAllTask($results, $datelabel);
            $usertask = $this->getAgentTaskData($users, $results, $datelabel);
            $alltask = $this->getAllTask($results, $datelabel);
        }
        return [
            'agent' => $userdata,
            'task'  => $datatask,
            'agentask' => $usertask,
            'alltask' => $alltask
        ];
    }

    //daily, monthly, yearly task
    public function getAllTask($results, $datelabel)
    {
        $temptaskempty = [];
        foreach ($results as $key => $item) {
            array_push($temptaskempty, $item->task);
        }
        $uniquetask = array_values(array_unique($temptaskempty));
        $datastorage = [];
        $labeldatastorage = [];
        foreach ($uniquetask as $key => $task) {
            $temptasktotal = [];
            $result_array = [];
            $position = 0;
            $total = 0;
            foreach ($results as $key => $value) {
                if ($task == $value->task) {
                    $total += $value->total;
                }
            }
            array_push($temptasktotal, $total);
            for ($x = 0; $x < count($temptasktotal); $x++) {
                if ($x == $position) {
                    $result_array[] = $task;
                }
                $result_array[] = $temptasktotal[$x];
            }
            array_push($datastorage, $result_array);
        }

        $labeldatastorage[0][] = $datelabel;
        array_push($labeldatastorage, "Total");
        return [
            'label' => $labeldatastorage,
            'task'  => $datastorage,
            'date'  => $datelabel,
        ];
    }

    //weekly task
    public function getWeeklyTask($results, $datelabel)
    {
        $temptaskempty = [];
        foreach ($results as $key => $item) {
            array_push($temptaskempty, $item->task);
        }
        $uniquetask = array_values(array_unique($temptaskempty));
        $weeklydata =  $this->getWeekDates($datelabel);
        $datastorage = [];
        foreach ($uniquetask as $key => $task) {
            $temptasktotal = [];
            $result_array = [];
            $position = 0;
            foreach ($weeklydata[1] as $key => $weekly) {
                $total = 0;
                foreach ($results as $key => $value) {
                    if ($task == $value->task) {
                        if ($value->datecreated >= $weekly['startdate'] && $value->datecreated <= $weekly['enddate']) {
                            $total += $value->total;
                        }
                    }
                }
                array_push($temptasktotal, $total);
            }
            for ($x = 0; $x < count($temptasktotal); $x++) {
                if ($x == $position) {
                    $result_array[] = $task;
                }
                $result_array[] = $temptasktotal[$x];
            }
            array_push($datastorage, $result_array);
        }
        $weeklydata[0][] = "Total";
        return [
            'label' => $weeklydata[0],
            'task'  => $datastorage,
            'date'  => $datelabel,
        ];
    }


    //daily, monthly, yearly agent
    public function getAgentData($users, $results, $datelabel)
    {
        $tempname = [];
        $labeldatastorage = [];
        foreach ($users as $key => $value) {
            array_push($tempname, $value->name);
        }

        $uniquename = array_values(array_unique($tempname));
        $datastorage = [];
        for ($i = 0; $i < count($uniquename); $i++) {
            $unique_name = $uniquename[$i];
            $temptasktotal = [];
            $result_array = [];
            $position = 0;
            $total = 0;
            foreach ($results as $key => $value) {
                if ($value->releasedby == $unique_name) {
                    //$fname = $value->releasedby;
                    $total += $value->total;
                }
            }
            array_push($temptasktotal, $total);
            for ($x = 0; $x < count($temptasktotal); $x++) {
                if ($x == $position) {
                    $result_array[] = $unique_name;
                }
                $result_array[] = $temptasktotal[$x];
            }
            array_push($datastorage, $result_array);
        }
        $labeldatastorage[0][] = $datelabel;
        array_push($labeldatastorage, "Total");
        return [
            'label' => $labeldatastorage,
            'task'  => $datastorage,
            'date'  => $datelabel,
        ];
    }

    //get the weekly for agent
    public function weeklyAgent($users, $results, $datelabel)
    {
        $weeklydata =  $this->getWeekDates($datelabel);
        // return $weeklydata[1];
        $tempname = [];
        foreach ($users as $key => $value) {
            array_push($tempname, $value->name);
        }
        $uniquename = array_values(array_unique($tempname));
        $datastorage = [];
        for ($i = 0; $i < count($uniquename); $i++) {
            $unique_name = $uniquename[$i];
            $temptasktotal = [];
            $result_array = [];
            $position = 0;
            foreach ($weeklydata[1] as $key => $weekly) {
                $total = 0;
                foreach ($results as $key => $value) {
                    if ($value->releasedby == $unique_name) {
                        //$fname = $value->releasedby;
                        if ($value->datecreated >= $weekly['startdate'] && $value->datecreated <= $weekly['enddate']) {
                            $total += $value->total;
                        }
                    }
                }
                array_push($temptasktotal, $total);
            }
            for ($x = 0; $x < count($temptasktotal); $x++) {
                if ($x == $position) {
                    $result_array[] = $unique_name;
                }
                $result_array[] = $temptasktotal[$x];
            }
            array_push($datastorage, $result_array);
        }
        $weeklydata[0][] = "Total";
        return [
            'label' => $weeklydata[0],
            'task'  => $datastorage,
            'date'  => $datelabel,
        ];
    }


    //daily, monthly, yearly task
    public function getAgentTaskData($users,$results, $datelabel)
    {
        $tempname = [];
        foreach ($users as $key => $value) {
            array_push($tempname, $value->name);
        }
        $temptaskempty = [];
        foreach ($results as $key => $item) {
            array_push($temptaskempty, $item->task);
        }
        $uniquetask = array_values(array_unique($temptaskempty));
        $uniquename = array_values(array_unique($tempname));
        $temparaay = [];
        $datastorage = [];
        for ($y = 0; $y < count($uniquename); $y++) { //task
            $uniquefullname = $uniquename[$y];
            $tempholderarray = [];
            $tasklistname = [];
            $result_array = [];
            $totaltask = 0;
            $position = 0;
            for ($i = 0; $i < count($uniquetask); $i++) {
                $count = 0;
                foreach ($results as $key => $value) {
                    if ($uniquefullname == $value->releasedby) {
                        if ($uniquetask[$i] == $value->task) {
                            //$fname = $value->releasedby;
                            $count += $value->total;
                        }
                    }
                }
                $totaltask += $count;
                array_push($tasklistname,  $uniquetask[$i]);
                array_push($tempholderarray, $count);
            }
            for ($x = 0; $x < count($tempholderarray); $x++) {
                if ($x == $position) {
                    $result_array[] = $uniquefullname;
                }
                $result_array[] = $tempholderarray[$x];
            }

            array_push($temparaay, $result_array);
        }

        array_push($tasklistname, "<b>Total</b>");
        array_push($datastorage, $tasklistname);
        array_push($datastorage, $temparaay);
        array_push($datastorage, $datelabel);


        return $datastorage;
    }

    //weekly task
    public function getTaskWeeklyData($results, $datelabel)
    {
       // set current date
       $date = $datelabel;
       // parse about any English textual datetime description into a Unix timestamp 
       $ts = strtotime($date);
       // calculate the number of days since Monday
       $dow = date('w', $ts);
       $offset = $dow - 1;
       if ($offset < 0) {
           $offset = 6;
       }
       // calculate timestamp for the Monday
       $ts = $ts - $offset * 86400;
       // loop from Monday till Sunday 
       $weeklydate = [];
       $weeklydatelabel = [];
       for ($i = 0; $i < 7; $i++, $ts += 86400) {
           array_push($weeklydate, date("Y-m-d", $ts));
           array_push($weeklydatelabel, date("l", $ts));
       }

       $temptaskempty = [];
        foreach ($results as $key => $item) {
            array_push($temptaskempty, $item->task);
        }
        $uniquetask = array_values(array_unique($temptaskempty));

       $datastorage = [];
       foreach ($uniquetask as $task) {
           $temptasktotal = [];
           $result_array = [];
           $position = 0;
           for ($i = 0; $i < count($weeklydate); $i++) {
               $total = 0;
               foreach ($results as $key => $value) {
                   if ($task === $value->task && $weeklydate[$i] == $value->datecreated) {
                       $total += $value->total;
                   }
               }
               array_push($temptasktotal, $total);
           }
           for ($x = 0; $x < count($temptasktotal); $x++) {
               if ($x == $position) {
                   $result_array[] = $task;
               }
               $result_array[] = $temptasktotal[$x];
           }
           array_push($datastorage, $result_array);
       }

       $weeklydatelabel[] = "Total";
       return [
           'label' => $weeklydatelabel,
           'task'  => $datastorage,
           'date'  => $datelabel,
       ];
    }


    //weekly label dates
    public function getWeekDates($monthsyear)
    {
        $months = date("m", strtotime($monthsyear));
        $year = date("Y", strtotime($monthsyear));
        $monthsuffix = date('M', strtotime($monthsyear));
        $month = intval($months);        //force month to single integer if '0x'
        $suff = array('st', 'nd', 'rd', 'th', 'th', 'th');       //week suffixes
        $end = date('t', mktime(0, 0, 0, $month, 1, $year));      //last date day of month: 28 - 31
        $start = date('w', mktime(0, 0, 0, $month, 7, $year));    //1st day of month: 0 - 6 (Mon - Sun)
        $last = 7 - $start;                     //get last day date (Ssun) of first week
        $noweeks = ceil((($end - ($last + 1)) / 7) + 1);      //total no. weeks in month
        $output = "";                    //initialize string     
        $weeklydatearray = [];
        $weeklyarraylabel = [];
        $dataarraydate = [];                 //initialize string     
        $monthlabel = str_pad($month, 2, '0', STR_PAD_LEFT);

        for ($x = 1; $x < $noweeks + 1; $x++) {
            if ($x == 1) {
                $startdate = "$year-$monthlabel-01";
                $day = $last - 6;
            } else {
                $day = $last + 1 + (($x - 2) * 7);
                $day = str_pad($day, 2, '0', STR_PAD_LEFT);
                $startdate = "$year-$monthlabel-$day";
            }
            if ($x == $noweeks) {
                $enddate = "$year-$monthlabel-$end";
            } else {
                $dayend = $day + 6;
                $dayend = str_pad($dayend, 2, '0', STR_PAD_LEFT);
                $enddate = "$year-$monthlabel-$dayend";
            }

            // array_push($weeklyarraylabel, $monthsuffix . " Week " . $x);
            array_push($weeklyarraylabel, "Week " . $x);
            $weeklydatearray[] = [
                'startdate' => $startdate,
                'enddate'   => $enddate,
            ];
        }

        array_push($dataarraydate, $weeklyarraylabel);
        array_push($dataarraydate, $weeklydatearray);

        return $dataarraydate;
    }
}
