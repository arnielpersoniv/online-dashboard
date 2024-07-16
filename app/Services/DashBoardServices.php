<?php

namespace App\Services;

use App\Helpers\DashboardAgentData;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class DashBoardServices
{

    public $user;

    public function __construct()
    {
        $this->model = new OpenInfraTaskService();
        $this->user = new User();
    }

    //daily
    public function getDaily($where)
    {
        $users = $this->user->where('role','agent')->whereNull('deleted_at')->get();
        $query = DB::table('open_infra_tasks as a')
            ->join('users as b', function ($join) {
                $join->on('a.agent_id', '=', 'b.id');
            })
            ->select('task','b.name as releasedby')
            ->whereNull('a.deleted_at');
            //->groupBy('task','b.name');


        if ($where['filter'] == 'all') {
            $date = date("F j, Y");
            $query = $query->whereDate('a.created_at', Carbon::today());
        } else {
            $query = $query->whereDate('a.created_at', $where['date']);
            $date = date("F j, Y", strtotime($where['date']));
        }
        $results = $query->get();
        return DashboardAgentData::dashboarddata($users, $results, $date);
    }

    //weekly
    public function getWeekly($where)
    {
        $users = $this->user->where('role','agent')->whereNull('deleted_at')->get();
        $query = DB::table('open_infra_tasks as a')
            ->join('users as b', function ($join) {
                $join->on('a.agent_id', '=', 'b.id');
            })
            ->select('task','b.name as releasedby')
            ->whereNull('a.deleted_at');
            //->groupBy('task','b.name');

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
        return DashboardAgentData::dashboarddata($users, $results, $date);
    }

    //monthly
    public function getMonthly($where)
    {
        $users = $this->user->where('role','agent')->whereNull('deleted_at')->get();
        $query = DB::table('open_infra_tasks as a')
            ->join('users as b', function ($join) {
                $join->on('a.agent_id', '=', 'b.id');
            })
            ->select('task','b.name as releasedby')
            ->whereNull('a.deleted_at');
            //->groupBy('task','b.name');

        if ($where['filter'] == 'all') {
            $date = date("F");
            $query = $query->whereMonth('a.created_at', Carbon::now()->month);
        } else {
            $month = explode("-", $where["date"]);
            $query = $query->whereMonth('a.created_at', $month[1]);
            $date = date("F", strtotime($where["date"]));
        }
        $results = $query->get();
        return DashboardAgentData::dashboarddata($users, $results, $date);
    }

    //yearly

    public function getYearly($where)
    {
        $users = $this->user->where('role','agent')->whereNull('deleted_at')->get();
        $query = DB::table('open_infra_tasks as a')
            ->join('users as b', function ($join) {
                $join->on('a.agent_id', '=', 'b.id');
            })
            ->select('task','b.name as releasedby')
            ->whereNull('a.deleted_at');
            //->groupBy('task','b.name');
        if ($where['filter'] == 'all') {
            $date = date("Y");
            $query = $query->whereYear('a.created_at', Carbon::now()->year);
        } else {
            $query = $query->whereYear('a.created_at', $where["date"]);
            $date = $where["date"];
        }
        $results = $query->get();
        return DashboardAgentData::dashboarddata($users, $results, $date);
    }
}
