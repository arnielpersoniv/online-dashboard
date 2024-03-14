<?php


namespace App\Helpers;
use Request;
use App\Models\Logs as LogActivityModel;


class LogActivity
{

    public static function addToLog($users, $subject, $action, $status)
    {
    	$log = [];
        $log['user_id']  = $users;
    	$log['subject']  = $subject;
    	$log['action']   = $action;
		$log['status']	 = $status;
    	$log['ip_address']   = Request::ip();
    	LogActivityModel::create($log);
    }

}