<?php


namespace App\Helpers;

class AgentRunningData
{

    public static function agentRunningData($results, $uniquedate, $date, $label)
    {
        $temptaskempty = [];
        foreach ($results as $key => $item) {
            array_push($temptaskempty, $item->task);
        }
        $uniquetask = array_values(array_unique($temptaskempty));
        $temparaay = [];
        $datastorage = [];
        for ($y = 0; $y < count($uniquedate); $y++) { //task
            $unique = $uniquedate[$y];
            $tempholderarray = [];
            $tasklistname = [];
            $result_array = [];
            $totaltask = 0;
            $position = 0;
            for ($i = 0; $i < count($uniquetask); $i++) {
                $count = 0;
                switch ($label["filter"]) {
                    case "daily":
                        $labeldate =  date("F j, Y", strtotime($unique));
                        break;
                    case "weekly":
                        $labeldate = date("F j, Y", strtotime($unique));
                        break;
                    case "monthly":
                        $labeldate = date("F Y", strtotime($date));
                        break;
                    default:
                        $labeldate = "YTD ".$unique;
                }
                foreach ($results as $key => $value) {
                    if ($label["filter"] == "weekly") {
                        if ($unique == $value->datecreated) {
                            if ($uniquetask[$i] == $value->task) {
                                $count += $value->total;
                            }
                        }
                    } else {
                        if ($uniquetask[$i] == $value->task) {
                            $count += $value->total;
                        }
                    }
                }
                $totaltask += $count;
                array_push($tasklistname,  $uniquetask[$i]);
                array_push($tempholderarray, $count);
            }
            for ($x = 0; $x < count($tempholderarray); $x++) {
                if ($x == $position) {
                    $result_array[] = $labeldate;
                }
                $result_array[] = $tempholderarray[$x];
            }

            //array_push($result_array, $totaltask);
            array_push($temparaay, $result_array);
        }

        array_push($tasklistname, "<b>Total</b>");
        array_push($datastorage, $tasklistname);
        array_push($datastorage, $temparaay);
        array_push($datastorage, $date);

        return $datastorage;
    }
}
