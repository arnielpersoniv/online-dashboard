<?php

namespace App\Services;

use App\Models\Task;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashBoardWipServices
{
    public function __construct()
    {
        $this->model = new Task();
    }

    //filter data
    public function getWeeklyAgent($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('users as b', function ($join) {
                $join->on('a.released_by', '=', 'b.id');
            })
            ->select('b.name as releasedby', DB::raw('DATE_FORMAT(a.created_at, "%Y-%m-%d") as datecreated'),)
            ->where('status', '<>', 'completed')
            ->whereNull('a.deleted_at');

        if ($where['filter'] == 'daily') {
            $query = $query->whereDate('a.created_at', $where['date']);
        }
        elseif($where['filter'] == 'weekly'){
            $datelabel = date("F", strtotime($where["date"]));
            $start = CarbonImmutable::parse($where['date']);
            $end = $start->addDays(6);
            $query = $query->whereBetween('a.created_at', [$start, $end]);
        }
        elseif($where['filter'] == 'monthly'){
            $month = explode("-",$where["date"]);
            $query = $query->whereMonth('a.created_at', $month[1]);
        }
        elseif($where['filter'] == 'yearly'){
            $query = $query->whereYear('a.created_at', $where["date"]);
        }  


        // if ($where['filter'] == 'all') {
        //     $monthYear = date("Y-m");
        //     $datelabel = date("F");
        //     $query = $query->whereBetween('a.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        // } else {
        //     $monthYear = $where['date'];
        //     $datelabel = date("F", strtotime($where["date"]));
        //     $start = CarbonImmutable::parse($where['date']);
        //     $end = $start->addDays(6);
        //     $query = $query->whereBetween('a.created_at', [$start, $end]);
        // }
        $results = $query->get();
        $weeklydata =  $this->getWeekDates($where['date']);

        // return $weeklydata[1];
        $tempname = [];
        foreach ($results as $key => $value) {
            array_push($tempname, $value->releasedby);
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
                        $fname = $value->releasedby;
                        if ($value->datecreated >= $weekly['startdate'] && $value->datecreated <= $weekly['enddate']) {
                            $total += 1;
                        }
                    }
                }
                array_push($temptasktotal, $total);
            }
            for ($x = 0; $x < count($temptasktotal); $x++) {
                if ($x == $position) {
                    $result_array[] = $fname;
                }
                $result_array[] = $temptasktotal[$x];
            }
            array_push($datastorage, $result_array);
        }
        if (count($uniquename) > 0) {
            $weeklydata[0][] = "Total";
            return [
                'label' => $weeklydata[0],
                'task'  => $datastorage,
                'date'  => $datelabel,
            ];
        } else {
            $weeklydata[0][] = "Total";
            return [
                'label' => $weeklydata[0],
                'task'  => [],
                'date'  => $datelabel,
            ];
        }
    }

    //weekly
    public function getWeeklyTask($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('tasks as b', function ($join) {
                $join->on('task_id', '=', 'b.id');
            })
            ->select('task_id', DB::raw('DATE_FORMAT(a.created_at, "%Y-%m-%d") as datecreated'),)
            ->where('status', '<>', 'completed')
            ->whereNull('a.deleted_at');

        if ($where['filter'] == 'all') {
            $monthYear = date("Y-m");
            $datelabel = date("F");
            $query = $query->whereBetween('a.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } else {
            $monthYear = $where['date'];
            $datelabel = date("F", strtotime($where["date"]));
            $start = CarbonImmutable::parse($where['date']);
            $end = $start->addDays(6);
            $query = $query->whereBetween('a.created_at', [$start, $end]);
        }
        $results = $query->get();
        $weeklydata =  $this->getWeekDates($monthYear);

        $datastorage = [];
        foreach ($taskdata as $key => $task) {
            $temptasktotal = [];
            $result_array = [];
            $position = 0;
            foreach ($weeklydata[1] as $key => $weekly) {
                $total = 0;
                foreach ($results as $key => $value) {
                    if ($task->id == $value->task_id) {
                        if ($value->datecreated >= $weekly['startdate'] && $value->datecreated <= $weekly['enddate']) {
                            $total += 1;
                        }
                    }
                }
                array_push($temptasktotal, $total);
            }
            for ($x = 0; $x < count($temptasktotal); $x++) {
                if ($x == $position) {
                    $result_array[] = $task->name;
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

    //weekly
    public function getWeekly($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('users as b', function ($join) {
                $join->on('a.released_by', '=', 'b.id');
            })
            ->join('tasks as c', function ($join) {
                $join->on('a.task_id', '=', 'c.id');
            })
            ->select('c.id', 'b.name as releasedby', 'a.task_id as task')
            ->where('status', '<>', 'completed')
            ->whereNull('a.deleted_at');

        if ($where['filter'] == 'all') {
            $date = date("F") . " " . date("d", strtotime(Carbon::now()->startOfWeek())) . "-" . date("d", strtotime(Carbon::now()->endOfWeek())) . ", " . date("Y");
            $query = $query->whereBetween('a.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } else {
            $start = CarbonImmutable::parse($where['date']);
            $end = $start->addDays(6);
            $query = $query->whereBetween('a.created_at', [$start, $end]);
            $date = date("F", strtotime($where['date'])) . " " . date("d", strtotime($start)) . "-" . date("d", strtotime($end)) . ", " . date("Y", strtotime($where['date']));
        }

        $results = $query->get();

        $tempholder = [];
        $tempname = [];
        foreach ($results as $key => $value) {
            array_push($tempname, $value->releasedby);
            array_push($tempholder, [
                'fname' => $value->releasedby,
                'taskid' => $value->task,
            ]);
        }
        $temptask = [];
        $temptaskempty = [];
        foreach ($taskdata as $key => $task) {
            array_push($temptaskempty, $task->name);
            array_push($temptask, [
                'taskname' => $task->name,
                'id' => $task->id,
            ]);
        }
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
            for ($i = 0; $i < count($temptask); $i++) {
                $count = 0;
                for ($z = 0; $z < count($tempholder); $z++) {
                    if ($tempholder[$z]['fname'] == $uniquefullname && $temptask[$i]['id'] == $tempholder[$z]['taskid']) {
                        $fname = $tempholder[$z]['fname'];
                        $count += 1;
                    }
                }
                $totaltask += $count;
                array_push($tasklistname,  $temptask[$i]['taskname']);
                array_push($tempholderarray, $count);
            }
            for ($x = 0; $x < count($tempholderarray); $x++) {
                if ($x == $position) {
                    $result_array[] = $fname;
                }
                $result_array[] = $tempholderarray[$x];
            }

            // array_push($result_array, $totaltask);
            array_push($temparaay, $result_array);
        }

        if (count($uniquename) > 0) {
            array_push($tasklistname, "<b>Total</b>");
            array_push($datastorage, $tasklistname);
            array_push($datastorage, $temparaay);
            array_push($datastorage, $date);
        } else {
            array_push($temptaskempty, "<b>Total</b>");
            array_push($datastorage, $temptaskempty);
            array_push($datastorage, []);
            array_push($datastorage, $date);
        }

        return $datastorage;
    }


    //monthly
    public function getMonthly($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('tasks as b', function ($join) {
                $join->on('a.task_id', '=', 'b.id');
            })
            ->select('name', DB::raw('COUNT(name) as total'))
            ->where('status', '<>', 'completed')
            ->groupBy(['name'])
            ->whereNull('a.deleted_at');

        if ($where['filter'] == 'all') {
            $date = date("F");
            $query = $query->whereMonth('a.created_at', Carbon::now()->month);
        } else {
            $month = explode("-", $where["date"]);
            $query = $query->whereMonth('a.created_at', $month[1]);
            $date = date("F", strtotime($where["date"]));
        }

        $results = $query->get();

        $datastorage = [];
        foreach ($taskdata as $task) {
            $total = 0;
            foreach ($results as $key => $value) {
                if ($task->name === $value->name) {
                    $total += $value->total;
                }
            }
            $datastorage[] = [
                'taskname' => $task->name,
                'total'     => $total,
            ];
        }

        return [
            'label' => $date,
            'task'  => $datastorage
        ];
    }

    //yearly

    public function getYearly($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('tasks as b', function ($join) {
                $join->on('a.task_id', '=', 'b.id');
            })
            ->select('name', DB::raw('COUNT(name) as total'))
            ->where('status', '=', 'completed')
            ->groupBy(['name'])
            ->whereNull('a.deleted_at');

        if ($where['filter'] == 'all') {
            $date = date("Y");
            $query = $query->whereYear('a.created_at', Carbon::now()->year);
        } else {
            $query = $query->whereYear('a.created_at', $where["date"]);
            $date = $where["date"];
        }

        $results = $query->get();

        $datastorage = [];
        foreach ($taskdata as $task) {
            $total = 0;
            foreach ($results as $key => $value) {
                if ($task->name === $value->name) {
                    $total += $value->total;
                }
            }
            $datastorage[] = [
                'taskname' => $task->name,
                'total'     => $total,
            ];
        }

        return [
            'label' => $date,
            'task'  => $datastorage
        ];
    }

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
