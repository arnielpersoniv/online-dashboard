<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AttendanceServices
{
    public function __construct()
    {
        $this->model = new User();
    }


    //weekly
    public function getAttendance($where)
    {
        $users = $this->model->where('role', 'agent')->whereNull('deleted_at')->get();
        $tempMonth = explode("-", $where);
        $month = $tempMonth[1];
        $year = $tempMonth[0];
        $query = DB::table('open_infra_tasks as a')
            ->join('users as b', function ($join) {
                $join->on('a.agent_id', '=', 'b.id');
            })
            ->select('name as releasedby', DB::raw('DATE_FORMAT(a.created_at, "%Y-%m-%d") as datecreated'),)
            ->where('role', 'agent')
            ->whereMonth('a.created_at', $month)
            ->whereNull('a.deleted_at')
            ->groupBy('releasedby', 'datecreated');

        $results = $query->get();

        $monthly = $this->dates_month($month, $year);

        $datastorage = [];
        $datastoragelabel = [];
        $tempdata = [];
        foreach ($monthly as $key => $month) {
            $temparray = [];
            $temparrayname = [];
            $result_array = [];
            $position = 0;
            $totalpresent = 0;
            $totalabsent = 0;
            $totalusers = 0;
            $attendancerate = 0;
            $tempstorage = [];
            $tempdatastorage = [];
            foreach ($users as $key => $user) {
                $status = "A";
                foreach ($results as $key => $value) {
                    if ($user->name == $value->releasedby) {
                        if ($month == $value->datecreated) {
                            $status = "P";
                        }
                    }
                }

                if ($status == "P") {
                    $totalpresent += 1;
                }
                if ($status == "A") {
                    $totalabsent += 1;
                }
                $totalusers = count($users);
                $attendancerate = ($totalpresent / $totalusers) * 100;
                array_push($temparray, $status);
                array_push($temparrayname, $user->name);

                $tempdatastorage[] = [
                    'name'   => $user->name,
                    'status' => $status,
                    'totalP' => $totalpresent,
                    'totalA' => $totalabsent,
                ];
            }

            for ($x = 0; $x < count($temparray); $x++) {
                if ($x == $position) {
                    $result_array[] = date("d-M", strtotime($month));
                }
                $result_array[] = $temparray[$x];
            }

            array_push($result_array, $totalpresent);
            array_push($result_array, $totalabsent);
            array_push($result_array, round($attendancerate) . "%");
            array_push($datastorage, $result_array);
            array_push($tempstorage, $temparray);

            $tempdata[] = $tempdatastorage;
        }

        array_push($temparrayname, "Total Present");
        array_push($temparrayname, "Total Absent");
        array_push($temparrayname, "Attendance Rate");
        array_push($datastoragelabel, $temparrayname);

        //return $tempdata;

        // Function to count "P" as present and "A" as absent per name
        function countPresentAbsentPerName($data, $monthly)
        {
            $statusCounts = [];
            $totalPresent = 0;
            $totalAbsent = 0;
            $totalRate = 0;
            foreach ($data as $group) {
                foreach ($group as $person) {
                    $name = $person["name"];
                    $status = $person["status"];
                    if (!isset($statusCounts[$name]["Present"])) {
                        $statusCounts[$name]["Present"] = 0;
                    }
                    if (!isset($statusCounts[$name]["Absent"])) {
                        $statusCounts[$name]["Absent"] = 0;
                    }

                    if ($status === "P") {
                        $statusCounts[$name]["Present"]++;
                    } elseif ($status === "A") {
                        $statusCounts[$name]["Absent"]++;
                    }
                }

                $totalPresent += $person["totalP"];
                $totalAbsent += $person["totalA"];

                $totalmonth = count($monthly);
                $totalRate = ($totalPresent / $totalmonth) * 100;

            }

            return [
                "data"      => $statusCounts,
                "totalp"    => $totalPresent,
                "totala"    => $totalAbsent,
                "totalrate" => $totalRate,
            ];
            //return $statusCounts;
        }

        // Get the total present and absent counts per name
        $totalPresentAbsentCounts = countPresentAbsentPerName($tempdata, $monthly);

        // Display the result
        $dataPresent = [];
        $dataAbsent = [];
        $dataRate = [];
        foreach ($totalPresentAbsentCounts["data"] as $name => $statusCounts) {
            array_push($dataPresent, $statusCounts["Present"] ?? 0);
            array_push($dataAbsent, $statusCounts["Absent"] ?? 0);
            $totalusers = count($monthly);
            $attendancerate = ($statusCounts["Present"] / $totalusers) * 100;
            array_push($dataRate, round($attendancerate)."%");
        }
        return [
            'label' => $datastoragelabel,
            'users'  => $datastorage,
            'totalP'  => $dataPresent,
            'totalA'  => $dataAbsent,
            'rate'  =>  $dataRate,
            'totalcolP'  =>  $totalPresentAbsentCounts["totalp"],
            'totalcolA'  =>  $totalPresentAbsentCounts["totala"],
            'totalcolRate'  =>  round($totalPresentAbsentCounts["totalrate"])."%",
        ];
    }

    public function dates_month($month, $year)
    {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $mktime = mktime(0, 0, 0, $month, $i, $year);
            $date = date("Y-m-d", $mktime);
            $dates_month[$i] = $date;
        }

        return $dates_month;
    }
}