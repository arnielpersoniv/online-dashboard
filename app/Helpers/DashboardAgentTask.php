<?php


namespace App\Helpers;

class DashboardAgentTask
{

    public static function dashboardtask($results,  $date)
    {
    	$datastorage = [];
        foreach ($results as $key => $value) {
            $datastorage[] = [
                'taskname' => $value->task,
                'total'     => $value->total,
            ];
        }
        return [
            'label' => $date,
            'task'  => $datastorage
        ];
    }

}