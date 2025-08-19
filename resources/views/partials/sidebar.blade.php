
   
<nav class="page-sidebar overflow-hidden sidebar1" id="sidebar">
    <div id="sidebar-collapse">
        @php $user = auth()->user(); @endphp
        @if($user)
            @if($user->role === 'Viewer')
                <div class="admin-block d-flex">
                    <div>
                        <img src="{{ asset('assets/img/viewer-avatar.png') }}" width="45px" />
                    </div>
                    <div class="admin-info">
                        <div class="font-strong">Viewer</div><small>User</small>
                    </div>
                </div>
                <ul class="side-menu metismenu">
                    <li>
                        <a href="{{ route('viewer.dashboard') }}" class="{{ Request::is('viewer/dashboard') ? 'active' : '' }}">
                            <i class="sidebar-item-icon fa fa-tv"></i>
                            <span class="nav-label">Viewer Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('viewer.borrow') }}" class="{{ Request::is('viewer/borrow') ? 'active' : '' }}">
                            <i class="sidebar-item-icon fa fa-hand-holding"></i>
                            <span class="nav-label">Borrow Item</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('viewer.borrow.history') }}" class="{{ Request::is('viewer/borrow/history') ? 'active' : '' }}">
                            <i class="sidebar-item-icon fa fa-history"></i>
                            <span class="nav-label">Borrow History</span>
                        </a>
                    </li>
                </ul>
            @else
                <div class="admin-block d-flex">
                    <div>
                        <img src="{{ asset('assets/img/admin-avatar.png') }}" width="45px" />
                    </div>
                    <div class="admin-info">
                        <div class="font-strong">Admin</div><small>Custodian</small>
                    </div>
                </div>
                <ul class="side-menu metismenu">
                    <li>
                        <a href="{{ url('dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">
                            <i class="sidebar-item-icon fa fa-th-large"></i>
                            <span class="nav-label">Dashboard</span>
                        </a>
                    </li>
                    <li class="heading">INVENTORY</li>
                    <li>
                        <a href="{{ url('scanner') }}" class="{{ Request::is('scanner') ? 'active' : '' }}">
                            <i class="sidebar-item-icon fa fa-qrcode"></i>
                            <span class="nav-label">Scanner</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('inventory/items') }}" class="{{ Request::is('inventory/items') ? 'active' : '' }}">
                            <i class="sidebar-item-icon fa fa-list"></i>
                            <span class="nav-label">View Items</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('inventory/create') }}" class="{{ Request::is('inventory/create') ? 'active' : '' }}">
                            <i class="sidebar-item-icon fa fa-plus-circle"></i>
                            <span class="nav-label">Add New Item</span>
                        </a>
                    </li>
                    
                    <li class="heading">BORROW MANAGEMENT</li>
                    <li>
                        <a href="{{ route('admin.borrow-requests') }}" class="{{ Request::is('admin/borrow-requests') ? 'active' : '' }}">
                            <i class="sidebar-item-icon fa fa-hand-holding"></i>
                            <span class="nav-label">All Borrow Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.borrow-requests.pending') }}" class="{{ Request::is('admin/borrow-requests/pending') ? 'active' : '' }}">
                            <i class="sidebar-item-icon fa fa-clock"></i>
                            <span class="nav-label">Pending Approvals</span>
                            <span class="badge badge-warning ml-auto" id="pending-count">0</span>
                        </a>
                    </li>
                   
                    <li class="heading">ACCOUNT</li>
                    <li>
                        <a href="{{ route('profile.index') }}">
                            <i class="sidebar-item-icon fa fa-user"></i>
                            <span class="nav-label">Profile</span>
                        </a>
                    </li>
                   
                </ul>
            @endif
        @endif
    </div>
</nav>
