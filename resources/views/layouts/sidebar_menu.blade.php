<!--side-menu-->
<div id="sidebar"><a href="{{url('/home')}}" class="visible-phone"><i class="fa fa-home icon-white"></i> Home</a>
    <ul>
        <!-- <li class="active"><a href="{{url('/home')}}"><i class="fa fa-home icon-white"></i> <span>Home</span></a> </li> -->
        @if($user->role == 'admin')
            <!-- <li><a href="{{url('/admin/dashboard')}}"><i class="fa fa-dashboard icon-white"></i> <span>Dashboard</span></a> </li> -->
            <li class="submenu"> <a href="{{url('/home')}}" class="tip-top" data-original-title="Main Dashboard"><i class="fa fa-dashboard icon-white"></i> <span>Dashboard</span></a>
                <ul>
                    <li><a href="{{url('/home')}}" target="_blank">Agent</a></li>
                    <li><a href="{{url('/admin/dashboard/task')}}" target="_blank">Tasks</a></li>
                    <li><a href="{{url('/admin/dashboard/wip')}}" target="_blank">Running Data</a></li>
                </ul>
            </li>
            <li><a href="{{url('/admin/attendance')}}" target="_blank" class="tip-top" data-original-title="Agent Attendance"><i class="fa fa-users icon-white"></i> <span>Attendance</span></a> </li>
            <!-- <li><a href="{{url('/admin/all/task')}}" target="_blank"><i class="fa fa-list icon-white"></i> <span>All Tasks</span></a> </li> -->
            <li><a href="{{url('/admin/all/agent-task')}}" target="_blank" class="tip-top" data-original-title="All Agent Tasks"><i class="fa fa-list icon-white"></i> <span>All Tasks</span></a> </li>
            <!-- <li><a href="{{url('/task/my-task')}}"><i class="fa fa-user icon-white"></i> <span>My Tasks</span></a> </li> -->
            <li><a href="{{url('/task/agent-task')}}" class="tip-top" data-original-title="Agent Task"><i class="fa fa-user icon-white"></i> <span>My Tasks</span></a> </li>
            <!-- <li class="submenu"> <a href="#"><i class="fa fa-list icon-white"></i> <span>Managements</span></a>
                <ul>
                    <li><a href="{{url('/admin/category')}}">Category</a></li>
                    <li><a href="{{url('/admin/task')}}">Tasks</a></li>
                    <li><a href="{{url('/admin/status')}}">Status</a></li>
                </ul>
            </li> -->
            <li class="active"><a href="{{url('/admin/users')}}" class="tip-top" data-original-title="Users"><i class="fa fa-users icon-white"></i> <span>Users</span></a> </li>
            <!-- <li class="submenu"> <a href="#"><i class="icon icon-user"></i> <span>Users</span></a>
                <ul>
                    <li><a href="{{url('/admin/permission')}}">Permission</a></li>
                    <li><a href="{{url('/admin/users')}}">Users</a></li>
                </ul>
            </li> -->
            <li><a href="{{url('/admin/logs')}}" class="tip-top" data-original-title="Users Log"><i class="fa fa-list icon-white"></i> <span>Logs</span></a></li>
        @else
            <!-- <li class="active"><a href="{{url('/task/my-task')}}"><i class="fa fa-user icon-white"></i> <span>My Tasks</span></a> </li> -->
            <li class="active"><a href="{{url('/task/agent-task')}}" class="tip-top" data-original-title="Agent Task"><i class="fa fa-user icon-white"></i> <span>My Tasks</span></a> </li>
        @endif
    </ul>
</div>
<!--end-side-menu-->