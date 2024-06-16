<?php


namespace App\Helpers;

class DashboardAgentData
{

    public static function dashboarddata($users, $results,  $date)
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
                            $count += 1;
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