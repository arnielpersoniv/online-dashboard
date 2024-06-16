<?php

namespace App\Services;

use App\Helpers\DashboardAgentTask;
use App\Models\OpenInfraTask;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashBoardTaskServices
{
    public function __construct()
    {
        $this->model = new OpenInfraTask();
    }

    //daily
    public function getDaily($where)
    {
        $query = $this->model->select('task', DB::raw('COUNT(task) as total'))
            //->where('status', '=', 'DONE')
            ->whereNull('deleted_at')
            ->groupBy('task');

        if ($where['filter'] == 'all') {
            $date = date("F j, Y");
            $querries = $query->whereDate('created_at', Carbon::today());
        } else {
            $querries = $query->whereDate('created_at', $where['date']);
            $date = date("F j, Y", strtotime($where['date']));
        }
        $results = $querries->get();
        return DashboardAgentTask::dashboardtask($results, $date);
    }

    //weekly
    public function getWeekly($where)
    {
        $query = $this->model->select('task', DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as datecreated'), DB::raw('COUNT(task) as total'))
            ->groupBy(['task', 'created_at']);

        if ($where['filter'] == 'all') {
            $weekdate = date('Y') . '-W' . date('W');
            $datelabel = date("F") . " " . date("d", strtotime(Carbon::now()->startOfWeek())) . "-" . date("d", strtotime(Carbon::now()->endOfWeek())) . ", " . date("Y");
            $queries = $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } else {
            $weekdate = $where['date'];
            $start = CarbonImmutable::parse($where['date']);
            $end = $start->addDays(6);
            $queries = $query->whereBetween('created_at', [$start, $end]);
            $datelabel = date("F", strtotime($where['date'])) . " " . date("d", strtotime($start)) . "-" . date("d", strtotime($end)) . ", " . date("Y", strtotime($where['date']));
        }

        $results = $queries->get();
        $temptaskempty = [];
        foreach ($results as $key => $item) {
            array_push($temptaskempty, $item->task);
        }
        $uniquetask = array_values(array_unique($temptaskempty));

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

        return [
            'label' => $weeklydatelabel,
            'task'  => $datastorage,
            'date'  => $datelabel,
        ];
    }

    //monthly
    public function getMonthly($where)
    {
        $query = $this->model->select('task', DB::raw('COUNT(task) as total'))
            //->where('status', '=', 'DONE')
            ->whereNull('deleted_at')
            ->groupBy('task');
        if ($where['filter'] == 'all') {
            $date = date("F");
            $query = $query->whereMonth('created_at', Carbon::now()->month);
        } else {
            $month = explode("-", $where["date"]);
            $query = $query->whereMonth('created_at', $month[1]);
            $date = date("F", strtotime($where["date"]));
        }
        $results = $query->get();
        return DashboardAgentTask::dashboardtask($results, $date);
    }

    //yearly

    public function getYearly($where)
    {
        $query = $this->model->select('task', DB::raw('COUNT(task) as total'))
        //->where('status', '=', 'DONE')
        ->whereNull('deleted_at')
        ->groupBy('task');

        if ($where['filter'] == 'all') {
            $date = date("Y");
            $query = $query->whereYear('created_at', Carbon::now()->year);
        } else {
            $query = $query->whereYear('created_at', $where["date"]);
            $date = $where["date"];
        }

        $results = $query->get();
        return DashboardAgentTask::dashboardtask($results, $date);

    }
}
