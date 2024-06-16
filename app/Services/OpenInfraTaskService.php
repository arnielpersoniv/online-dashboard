<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\OpenInfraTask;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;

class OpenInfraTaskService
{

    protected $model;

    public function __construct()
    {
        $this->model = new OpenInfraTask();
    }

    public function loadAll($data)
    {

        $query = $this->model->with('agent')
            ->whereNull('deleted_at')
            ->orderByDesc('id');
        if ($data['params'] =='agent'){
            $queries = $query->where('agent_id', auth()->user()->id);
        }
        if($data['filter'] =='daily'){
            $queries = $query->whereDate('created_at', $data['data']);
        }
        else if($data['filter'] =='weekly'){
            $start = CarbonImmutable::parse($data['data']);
            $end = $start->addDays(6);
            $queries = $query->whereBetween('created_at', [$start, $end]);
        }
        else if($data['filter'] =='monthly'){
            $month = explode("-",$data["data"]);
            $queries = $query->whereMonth('created_at', $month[1]);
        }
        else if($data['filter'] =='yearly'){
            $queries = $query->whereYear('created_at', $data["data"]);
        }


        $results = $queries->get();

        $datastorage = [];
        $tempstorage = [];
        foreach ($results as $key => $value) {
            $count = 0;
            //if ($value->task == $value->task && $value->status == 'Done') {
            if ($value->task == $value->task) {
                $tempstorage[] = [
                    'task'      => $value->task,
                    'total'     => $count + 1
                ];
            }

            //time start and end
            $timestart = strtotime($value->time_start);
            $timeend = strtotime($value->time_end);
            $secondscompleted = $timeend - $timestart;

            $hours = floor($secondscompleted / 3600);
            $minutes = floor(($secondscompleted / 60) % 60);
            $seconds = $secondscompleted % 60;
            
            $totalspent = $hours.'hr'.' '.$minutes. 'min'. ' '.$seconds.'sec';

            $datastorage[] = [
                'id'         => $value->id,
                'lid_no'     => $value->lid_no,
                'category'   => $value->category,
                'task'       => $value->task,
                'agent'      => $value->agent->name,
                'profile'    => $value->agent->profile,
                'status'     => $value->status,
                'adhoc'      => ($value->adhoc != null) ? $value->adhoc : '',
                'time_spent' => ($value->time_end != null) ? $totalspent : '-',
                'created_at' => date("m/d/Y", strtotime($value->created_at)),
            ];
        }

        $total = 0;
        $group = [];
        foreach ($tempstorage as $totalcount) {
            if ($totalcount['task'] === $totalcount['task']) {
                $total += $totalcount['total'];
                $key = $totalcount['task'];
                if (!array_key_exists($key, $group)) {
                    $group[$key] = array(
                        'name'       => $totalcount['task'],
                        'total'      => $totalcount['total'],
                    );
                } else {
                    $group[$key]['total'] = $group[$key]['total'] + $totalcount['total'];
                };
            }
        }

        $finalstorage = [];
        foreach ($group as $key => $value) {
            $finalstorage[] = [
                'name'  => $value['name'],
                'total' => $value['total']
            ];
        }

        return [
            'total'       => $total,
            'total_count' => $finalstorage,
            'details'     => $datastorage
        ];
    }
}
