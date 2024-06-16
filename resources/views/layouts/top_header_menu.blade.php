<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
    <ul class="nav">
        <li class=""><a title="" href="#"><i class="icon icon-user"></i> <span class="text">Welcome! <strong>{{Auth::user()->name}}</strong></span></a></li>
        <li class=""><a title="" href="#" id="change_profile" class="tip-bottom" data-original-title="Change Photo"><i class="icon icon-user"></i> <span class="text">Profile</span></a></li>
        <!-- <li class=" dropdown" id="menu-messages"><a href="#" data-toggle="dropdown" data-target="#menu-messages" class="dropdown-toggle"><i class="icon icon-envelope"></i> <span class="text">Messages</span> <span class="label label-important">5</span> <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a class="sAdd" title="" href="#">new message</a></li>
                <li><a class="sInbox" title="" href="#">inbox</a></li>
                <li><a class="sOutbox" title="" href="#">outbox</a></li>
                <li><a class="sTrash" title="" href="#">trash</a></li>
            </ul>
        </li>
        <li class=""><a title="" href="#"><i class="icon icon-cog"></i> <span class="text">Settings</span></a></li> -->
        <li class=""><a title="" href="#" id="btn_logout" class="tip-bottom" data-original-title="Exit"><i class="icon icon-off"></i> <span class="text">Logout</span></a></li>
    </ul>
</div>
<div id="search">
    <input type="text" id="searchbox" placeholder="Search here..." />
    <button type="button" class="tip-left" title="Search"><i class="icon-search icon-white"></i></button>
</div>
<form id="logout-form" action="{{ route('logout') }}" method="POST">
    @csrf
</form>
<!--close-top-Header-menu-->