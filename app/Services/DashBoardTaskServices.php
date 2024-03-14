<?php

namespace App\Services;

use App\Models\Task;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashBoardTaskServices
{
    public function __construct()
    {
        $this->model = new Task();
    }

    //daily
    public function getDaily($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('tasks as b', function ($join) {
                $join->on('a.task_id', '=', 'b.id');
            })
            ->select('task_id', DB::raw('COUNT(task_id) as total'))
            ->where('status', '=', 'completed')
            ->whereNull('a.deleted_at')
            ->groupBy('task_id');

        if ($where['filter'] == 'all') {
            $date = date("F j, Y");
            $query = $query->whereDate('a.created_at', Carbon::today());
        } else {
            $query = $query->whereDate('a.created_at', $where['date']);
            $date = date("F j, Y", strtotime($where['date']));
        }
        $results = $query->get();

        $datastorage = [];
        foreach ($taskdata as $task) {
            $total = 0;
            foreach ($results as $key => $value) {
                if ($task->id === $value->task_id) {
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

    //weekly
    public function getWeekly($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('tasks as b', function ($join) {
                $join->on('a.task_id', '=', 'b.id');
            })
            ->select('task_id', DB::raw('DATE_FORMAT(a.created_at, "%Y-%m-%d") as datecreated'), DB::raw('COUNT(task_id) as total'))
            ->where('status', '=', 'completed')
            ->groupBy(['task_id', 'a.created_at'])
            ->whereNull('a.deleted_at');

        if ($where['filter'] == 'all') {
            $weekdate = date('Y') . '-W' . date('W');
            $datelabel = date("F") . " " . date("d", strtotime(Carbon::now()->startOfWeek())) . "-" . date("d", strtotime(Carbon::now()->endOfWeek())) . ", " . date("Y");
            $query = $query->whereBetween('a.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } else {
            $weekdate = $where['date'];
            $start = CarbonImmutable::parse($where['date']);
            $end = $start->addDays(6);
            $query = $query->whereBetween('a.created_at', [$start, $end]);
            $datelabel = date("F", strtotime($where['date'])) . " " . date("d", strtotime($start)) . "-" . date("d", strtotime($end)) . ", " . date("Y", strtotime($where['date']));
        }

        $results = $query->get();

        // set current date
        $date = $weekdate;
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

        $datastorage = [];
        foreach ($taskdata as $task) {
            $temptasktotal = [];
            $result_array = [];
            $position = 0;
            for ($i = 0; $i < count($weeklydate); $i++) {
                $total = 0;
                foreach ($results as $key => $value) {
                    if ($task->id === $value->task_id && $weeklydate[$i] == $value->datecreated) {
                        $total += $value->total;
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

        return [
            'label' => $weeklydatelabel,
            'task'  => $datastorage,
            'date'  => $datelabel,
        ];
    }

    //monthly
    public function getMonthly($where)
    {
        $taskdata = $this->model->whereNull('deleted_at')->get();
        $query = DB::table('activities as a')
            ->join('tasks as b', function ($join) {
                $join->on('a.task_id', '=', 'b.id');
            })
            ->select('task_id', DB::raw('COUNT(task_id) as total'))
            ->where('status', '=', 'completed')
            ->groupBy(['task_id'])
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
                if ($task->id === $value->task_id) {
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
            ->select('task_id', DB::raw('COUNT(task_id) as total'))
            ->where('status', '=', 'completed')
            ->groupBy(['task_id'])
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
                if ($task->id === $value->task_id) {
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
}
