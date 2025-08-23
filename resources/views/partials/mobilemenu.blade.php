<nav class="mobile-nav" id="mobileMenu">
    @php $user = auth()->user(); @endphp
    @if($user)
        @if($user->role === 'Viewer')
            <ul class="mobile-menu-list">
                <li><a href="{{ route('viewer.dashboard') }}"><i class="fa fa-tv"></i> Viewer Dashboard</a></li>
                <li><a href="{{ route('viewer.borrow') }}"><i class="fa fa-hand-holding"></i> Borrow Item</a></li>
                <li><a href="{{ route('viewer.borrow.history') }}"><i class="fa fa-history"></i> Borrow History</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="mobile-logout-btn"><i class="fa fa-power-off"></i> Logout</button>
                    </form>
                </li>
            </ul>
        @else
            <ul class="mobile-menu-list">
                <li><a href="{{ url('dashboard') }}"><i class="fa fa-th-large"></i> Dashboard</a></li>
                <li class="heading">INVENTORY</li>
                <li><a href="{{ url('scanner') }}"><i class="fa fa-qrcode"></i> Scanner</a></li>
                <li><a href="{{ url('inventory/items') }}"><i class="fa fa-list"></i> View Items</a></li>
                <li><a href="{{ route('inventory.available-items') }}"><i class="fa fa-boxes"></i> Available Items</a></li>
                <li><a href="{{ url('inventory/create') }}"><i class="fa fa-plus-circle"></i> Add New Item</a></li>
                <li class="heading">BORROW MANAGEMENT</li>
                <li><a href="{{ route('admin.borrow-requests') }}"><i class="fa fa-hand-holding"></i> All Borrow Requests</a></li>
                <li><a href="{{ route('admin.borrow-requests.pending') }}"><i class="fa fa-clock"></i> Pending Approvals</a></li>
                <li class="heading">ACCOUNT</li>
                <li><a href="{{ route('profile.index') }}"><i class="fa fa-user"></i> Profile</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                        @csrf
                        <button type="submit" class="mobile-logout-btn"><i class="fa fa-power-off"></i> Logout</button>
                    </form>
                </li>
            </ul>
        @endif
    @endif
</nav>
