<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    private $credentials;

    /**Getter and Setter */

    public function setCredentials()
    {
        $this->credentials = Auth::user();
    }

    public function getCredentials()
    {
        return $this->credentials;
    }
    
    /*View Page*/

    public function showHome()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('home',compact('user'));
    }

    public function showDashBoardAgent()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.dashboard.agent',compact('user'));
    }

    public function showDashBoardTask()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.dashboard.task',compact('user'));
    }

    public function showDashBoardWip()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.dashboard.wip',compact('user'));
    }

    public function showTaskAll()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.dashboard.all',compact('user'));
    }

    public function showAgentTaskAll()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.dashboard.agent-all',compact('user'));
    }

    public function showAttendance()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.attendance.all',compact('user'));
    }

    public function showMyTasks()
    {
        $this->setCredentials();
        $user = $this->getCredentials();
        return view('users.agent.all',compact('user'));
    }

    public function showAgentTasks()
    {
        $this->setCredentials();
        $user = $this->getCredentials();
        return view('users.agent.agent-infra',compact('user'));
    }

    public function showCategory()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.managements.category.all',compact('user'));
    }

    public function showTasks()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.managements.tasks.all',compact('user'));
    }

    public function showStatus()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.managements.status.all',compact('user'));
    }
    public function showPermissions()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.managements.permission.all',compact('user'));
    }
    public function showUsers()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.managements.users.all',compact('user'));
    }
    
    public function showLogs()
    {
        $this->setCredentials();
        $user = $this->getCredentials();

        return view('admin.logs.all',compact('user'));
    }
}
