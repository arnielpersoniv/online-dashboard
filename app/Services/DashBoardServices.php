<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashBoardServices
{

    public $user;

    public function __construct()
    {
        $this->model = new Task();
        $this->user = new User();
    }

    //daily
    public function getDaily($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $users = $this->user->where('role','agent')->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('users as b', function ($join) {
                $join->on('a.released_by', '=', 'b.id');
            })
            ->join('tasks as c', function ($join) {
                $join->on('a.task_id', '=', 'c.id');
            })
            ->select('c.id', 'b.name as releasedby', 'a.task_id')
            // ->where('status', '=', 'completed')
            ->whereNull('a.deleted_at');

        if ($where['filter'] == 'all') {
            $date = date("F j, Y");
            $query = $query->whereDate('a.created_at', Carbon::today());
        } else {
            $query = $query->whereDate('a.created_at', $where['date']);
            $date = date("F j, Y", strtotime($where['date']));
        }

        $results = $query->get();
        $tempname = [];
        foreach ($users as $key => $value) {
            array_push($tempname, $value->name);
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
                foreach ($results as $key => $value) {
                    if ($uniquefullname == $value->releasedby) {
                        if ($temptask[$i]['id'] == $value->task_id) {
                            //$fname = $value->releasedby;
                            $count += 1;
                        }
                    }
                }
                $totaltask += $count;
                array_push($tasklistname,  $temptask[$i]['taskname']);
                array_push($tempholderarray, $count);
            }
            for ($x = 0; $x < count($tempholderarray); $x++) {
                if ($x == $position) {
                    $result_array[] = $uniquefullname;
                }
                $result_array[] = $tempholderarray[$x];
            }

            // array_push($result_array, $totaltask);
            array_push($temparaay, $result_array);
        }

        array_push($tasklistname, "<b>Total</b>");
        array_push($datastorage, $tasklistname);
        array_push($datastorage, $temparaay);
        array_push($datastorage, $date);

        return $datastorage;
    }

    //weekly
    public function getWeekly($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $users = $this->user->where('role','agent')->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('users as b', function ($join) {
                $join->on('a.released_by', '=', 'b.id');
            })
            ->join('tasks as c', function ($join) {
                $join->on('a.task_id', '=', 'c.id');
            })
            ->select('c.id', 'b.name as releasedby', 'a.task_id')
            // ->where('status', '=', 'completed')
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
        $tempname = [];
        foreach ($users as $key => $value) {
            array_push($tempname, $value->name);
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
                foreach ($results as $key => $value) {
                    if ($uniquefullname == $value->releasedby) {
                        if ($temptask[$i]['id'] == $value->task_id) {
                            //$fname = $value->releasedby;
                            $count += 1;
                        }
                    }
                }
                $totaltask += $count;
                array_push($tasklistname,  $temptask[$i]['taskname']);
                array_push($tempholderarray, $count);
            }
            for ($x = 0; $x < count($tempholderarray); $x++) {
                if ($x == $position) {
                    $result_array[] = $uniquefullname;
                }
                $result_array[] = $tempholderarray[$x];
            }

            // array_push($result_array, $totaltask);
            array_push($temparaay, $result_array);
        }

        array_push($tasklistname, "<b>Total</b>");
        array_push($datastorage, $tasklistname);
        array_push($datastorage, $temparaay);
        array_push($datastorage, $date);


        return $datastorage;
    }

    //monthly
    public function getMonthly($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $users = $this->user->where('role','agent')->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('users as b', function ($join) {
                $join->on('a.released_by', '=', 'b.id');
            })
            ->join('tasks as c', function ($join) {
                $join->on('a.task_id', '=', 'c.id');
            })
            ->select('c.id', 'b.name as releasedby', 'a.task_id')
            // ->where('status', '=', 'completed')
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

        $tempname = [];
        foreach ($users as $key => $value) {
            array_push($tempname, $value->name);
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
                foreach ($results as $key => $value) {
                    if ($uniquefullname == $value->releasedby) {
                        if ($temptask[$i]['id'] == $value->task_id) {
                            //$fname = $value->releasedby;
                            $count += 1;
                        }
                    }
                }
                $totaltask += $count;
                array_push($tasklistname,  $temptask[$i]['taskname']);
                array_push($tempholderarray, $count);
            }
            for ($x = 0; $x < count($tempholderarray); $x++) {
                if ($x == $position) {
                    $result_array[] = $uniquefullname;
                }
                $result_array[] = $tempholderarray[$x];
            }

            // array_push($result_array, $totaltask);
            array_push($temparaay, $result_array);
        }

        array_push($tasklistname, "<b>Total</b>");
        array_push($datastorage, $tasklistname);
        array_push($datastorage, $temparaay);
        array_push($datastorage, $date);

        return $datastorage;
    }

    //yearly

    public function getYearly($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $users = $this->user->where('role','agent')->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('users as b', function ($join) {
                $join->on('a.released_by', '=', 'b.id');
            })
            ->join('tasks as c', function ($join) {
                $join->on('a.task_id', '=', 'c.id');
            })
            ->select('c.id', 'b.name as releasedby', 'a.task_id')
            ->where('status', '=', 'completed')
            ->whereNull('a.deleted_at');

        if ($where['filter'] == 'all') {
            $date = date("Y");
            $query = $query->whereYear('a.created_at', Carbon::now()->year);
        } else {
            $query = $query->whereYear('a.created_at', $where["date"]);
            $date = $where["date"];
        }

        $results = $query->get();
        $tempname = [];
        foreach ($users as $key => $value) {
            array_push($tempname, $value->name);;
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
                foreach ($results as $key => $value) {
                    if ($uniquefullname == $value->releasedby) {
                        if ($temptask[$i]['id'] == $value->task_id) {
                            //$fname = $value->releasedby;
                            $count += 1;
                        }
                    }
                }
                $totaltask += $count;
                array_push($tasklistname,  $temptask[$i]['taskname']);
                array_push($tempholderarray, $count);
            }
            for ($x = 0; $x < count($tempholderarray); $x++) {
                if ($x == $position) {
                    $result_array[] = $uniquefullname;
                }
                $result_array[] = $tempholderarray[$x];
            }

            // array_push($result_array, $totaltask);
            array_push($temparaay, $result_array);
        }

        array_push($tasklistname, "<b>Total</b>");
        array_push($datastorage, $tasklistname);
        array_push($datastorage, $temparaay);
        array_push($datastorage, $date);

        return $datastorage;
    }
}
