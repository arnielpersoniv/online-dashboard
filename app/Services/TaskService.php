<?php

namespace App\Services;

use App\Models\Activity;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTime;

class TaskService
{

    protected $model;

    public function __construct()
    {
        $this->model = new Activity();
    }

    public function loadAll($data)
    {

        $query = $this->model->with('releasedby', 'category', 'task')
            // ->whereBetween('created_at', [$data['start'], $data['end']])
            ->whereNull('deleted_at')
            ->orderBy('id');
        if ($data['params'] =='agent'){
            $queries = $query->where('released_by', auth()->user()->id);
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
            if ($value->category_id == $value->category_id && $value->status == 'completed') {
                $tempstorage[] = [
                    'category' => $value->category->name,
                    'total'    => $count + 1
                ];
            }

            //time start and end
            $timestart = strtotime($value->time_start);
            $timeend = strtotime($value->time_end);
            $secondscompleted = $timeend - $timestart;

            //time hold and resume
            $timehold = strtotime($value->time_hold);
            $timeresume = strtotime($value->time_resume);
            $secondresume = $timeresume - $timehold;
            $timespent = 0;
            if($value->time_hold != null && $value->time_resume != null){
                $timespent = $secondscompleted - $secondresume;
            }
            if($value->time_hold == null && $value->time_resume == null){
                $timespent = $secondscompleted;
            }

            $hours = floor($timespent / 3600);
            $minutes = floor(($timespent / 60) % 60);
            $seconds = $timespent % 60;
            
            $totalspent = $hours.'hr'.' '.$minutes. 'min';

            $datastorage[] = [
                'id'         => $value->id,
                'order_no'   => $value->order_no,
                'account_no' => $value->account_no,
                'category'   => $value->category->name,
                'task'       => $value->task->name,
                'releasedby' => $value->releasedby->name,
                'status'     => $value->status,
                'time_hold'  => $value->time_hold,
                'time_resume'=> $value->time_resume,
                'hold_reason'=> ($value->time_hold != null) ? $value->hold_reason : '',
                'time_spent' => ($value->time_end != null) ? $totalspent : '-',
                'created_at' => date("m/d/Y", strtotime($value->created_at)),
            ];
        }

        $total = 0;
        $group = [];
        foreach ($tempstorage as $totalcount) {
            if ($totalcount['category'] === $totalcount['category']) {
                $total += $totalcount['total'];
                $key = $totalcount['category'];
                if (!array_key_exists($key, $group)) {
                    $group[$key] = array(
                        'name'       => $totalcount['category'],
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
