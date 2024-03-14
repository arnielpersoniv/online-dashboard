<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
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
        $users = $this->model->where('role','agent')->whereNull('deleted_at')->get();
        $tempMonth = explode("-", $where);
        $month = $tempMonth[1];
        $year = $tempMonth[0];
        $query = DB::table('activities as a')
            ->join('users as b', function ($join) {
                $join->on('a.released_by', '=', 'b.id');
            })
            ->select('b.name as releasedby', DB::raw('DATE_FORMAT(a.created_at, "%Y-%m-%d") as datecreated'),)
            ->whereMonth('a.created_at', $month)
            ->whereNull('a.deleted_at');

        $results = $query->get();

        $monthly = $this->dates_month($month, $year);

        $datastorage = [];
        $tempstorage = [];
        $datastoragelabel = [];
        foreach ($monthly as $key => $month) {
            $temparray = [];
            $temparrayname = [];
            $result_array = [];
            $position = 0;
            $totalpresent = 0;
            $totalabsent = 0;
            $totalusers = 0;
            $attendancerate = 0;
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
        }

        array_push($temparrayname, "Total Present");
        array_push($temparrayname, "Total Absent");
        array_push($temparrayname, "Attendance Rate");
        array_push($datastoragelabel, $temparrayname);

        return [
            'label' => $datastoragelabel,
            'users'  => $datastorage,
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
