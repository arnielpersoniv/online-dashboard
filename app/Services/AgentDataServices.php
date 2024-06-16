<?php

namespace App\Services;

use App\Helpers\AgentRunningData;
use App\Helpers\DashboardAgentData;
use App\Models\OpenInfraTask;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

class AgentDataServices
{
    public $model;

    public function __construct()
    {
        $this->model = new OpenInfraTask();
    }

    //daily
    public function showAgentData($where)
    {
        $query = $this->model->select('task', DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as datecreated'), DB::raw('COUNT(task) as total'))
                            ->where('agent_id',auth()->user()->id)
                            //->where('status','Pending')
                            ->groupBy(['task', 'datecreated']);
        if ($where['filter'] == 'daily') {
            $queries = $query->whereDate('created_at', $where['date']);
            $date = date("F j, Y", strtotime($where['date']));
            $uniquedate[] = $where['date'];
        } else if ($where['filter'] == 'weekly') {
            $weekdate = $where['date'];
            $start = CarbonImmutable::parse($where['date']);
            $end = $start->addDays(6);
            $queries = $query->whereBetween('created_at', [$start, $end]);
            $date = date("F", strtotime($where['date'])) . " " . date("d", strtotime($start)) . "-" . date("d", strtotime($end)) . ", " . date("Y", strtotime($where['date']));
            // set current date
            $dates = $weekdate;
            // parse about any English textual datetime description into a Unix timestamp 
            $ts = strtotime($dates);
            // calculate the number of days since Monday
            $dow = date('w', $ts);
            $offset = $dow - 1;
            if ($offset < 0) {
                $offset = 6;
            }
            // calculate timestamp for the Monday
            $ts = $ts - $offset * 86400;
            // loop from Monday till Sunday 
            $uniquedate = [];
            $weeklydatelabel = [];
            for ($i = 0; $i < 7; $i++, $ts += 86400) {
                array_push($uniquedate, date("Y-m-d", $ts));
                array_push($weeklydatelabel, date("l", $ts));
            }
        } else if ($where['filter'] == 'monthly') {
            $month = explode("-", $where["date"]);
            $queries = $query->whereMonth('created_at', $month[1]);
            $date = date("F Y", strtotime($where["date"]));
            $uniquedate[] = $month[1];
        } else if ($where['filter'] == 'yearly') {
            $queries = $query->whereYear('created_at', $where["date"]);
            $date = $where["date"];
            $uniquedate[] = $where['date'];
        }
        $results = $queries->get();

        return AgentRunningData::agentRunningData($results, $uniquedate, $date, $where);
    }
}
