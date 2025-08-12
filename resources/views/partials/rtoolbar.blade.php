<ul class="nav navbar-toolbar">
    <li class="dropdown dropdown-inbox">
      
        <ul class="dropdown-menu dropdown-menu-right dropdown-menu-media">
           
           
   
            <li class="list-group list-group-divider scroller" data-height="240px" data-color="#71808f">
                <div>
                   
                   
                   
                   
                </div>
            </li>
        </ul>
    </li>
    <li class="dropdown dropdown-user">
        <a class="nav-link dropdown-toggle link" data-toggle="dropdown">
            <img src="{{ asset('assets/img/admin-avatar.png') }}" />
            <span></span>{{ Auth::user()->name }}<i class="fa fa-angle-down m-l-5"></i></a>
        <ul class="dropdown-menu dropdown-menu-right">
           
            <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fa fa-user"></i>Profile</a>
            <!-- <a class="dropdown-item" href="profile.html"><i class="fa fa-cog"></i>Settings</a> -->
            <li class="dropdown-divider"></li>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item">
                    <i class="fa fa-power-off"></i> Logout
                </button>
            </form>
            
        </ul>
    </li>
</ul>