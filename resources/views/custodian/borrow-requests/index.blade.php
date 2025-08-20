<x-main-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('assets/css/custodian-borrow-requests.css') }}" rel="stylesheet">
    
    <div class="scanner-container">
        <!-- Success Notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Error Notification -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Borrow Requests Header -->
        <div class="scanner-header">
            <div class="header-content">
                <h1 class="scanner-title">
                    <i class="fas fa-hand-holding"></i> Borrow Requests Management
                </h1>
                <div class="header-stats">
                    <span class="stat-item">
                        <i class="fas fa-list"></i> 
                        <span>{{ $borrowRequests->count() }}</span> Total Requests
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-clock"></i>
                        <span>{{ $pendingCount }}</span> Pending
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-check"></i>
                        <span>{{ $approvedCount }}</span> Approved
                    </span>
                </div>
            </div>
        </div>

        <div class="scanner-content">
            <!-- Filter Section -->
            <div class="items-section mb-4">
                <div class="items-header">
                    <h3><i class="fas fa-filter"></i> Filter Requests</h3>
                    <div class="items-controls">
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.borrow-requests') }}" class="btn btn-sm {{ request('status') ? 'btn-secondary' : 'btn-primary' }}">
                                All
                            </a>
                            <a href="{{ route('admin.borrow-requests', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-primary' : 'btn-secondary' }}">
                                Pending
                            </a>
                            <a href="{{ route('admin.borrow-requests', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') == 'approved' ? 'btn-primary' : 'btn-secondary' }}">
                                Approved
                            </a>
                            <a href="{{ route('admin.borrow-requests', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-secondary' }}">
                                Rejected
                            </a>
                            <a href="{{ route('admin.borrow-requests', ['status' => 'returned']) }}" class="btn btn-sm {{ request('status') == 'returned' ? 'btn-primary' : 'btn-secondary' }}">
                                Returned
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requests Table -->
            <div class="items-table-container">
                <div class="table-responsive">
                    <table class="table table-hover table-sm" id="borrowRequestsTable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Purpose</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($borrowRequests as $request)
                                <tr>
                                    <td>{{ $request->id }}</td>
                                    <td>
                                        <div class="user-info">
                                            <strong>{{ $request->user->name }}</strong>
                                            <small class="text-muted">{{ $request->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="item-info">
                                            <strong>{{ $request->item->item_name }}</strong>
                                            <small class="text-muted">{{ $request->item->room->name ?? 'N/A' }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $request->quantity }}</td>
                                    <td>
                                        <span class="text-truncate" style="max-width: 200px; display: inline-block;">
                                            {{ Str::limit($request->purpose, 50) }}
                                        </span>
                                    </td>
                                    <td>{{ $request->return_date->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge {{ $request->getStatusBadgeClass() }}">
                                            {{ $request->getStatusText() }}
                                        </span>
                                        @if($request->isOverdue())
                                            <span class="badge badge-danger ml-1">Overdue</span>
                                        @endif
                                    </td>
                                    <td>{{ $request->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.borrow-requests.show', $request->id) }}" 
                                               class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($request->status === 'pending')
                                                <button type="button" 
                                                        class="btn btn-success btn-sm approve-request" 
                                                        data-id="{{ $request->id }}"
                                                        title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-danger btn-sm reject-request" 
                                                        data-id="{{ $request->id }}"
                                                        title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox fa-3x"></i>
                                            <h4>No borrow requests found</h4>
                                            <p>There are no borrow requests matching your criteria.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $borrowRequests->links() }}
            </div>
        </div>
    </div>

    <script>
        // Handle approve/reject actions
        document.addEventListener('DOMContentLoaded', function() {
            // Approve request
            document.querySelectorAll('.approve-request').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to approve this borrow request?')) {
                        const requestId = this.dataset.id;
                        fetch(`/admin/borrow-requests/${requestId}/approve`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message || 'Error approving request');
                            }
                        });
                    }
                });
            });

            // Reject request
            document.querySelectorAll('.reject-request').forEach(button => {
                button.addEventListener('click', function() {
                    const reason = prompt('Please provide a reason for rejection:');
                    if (reason && reason.trim() !== '') {
                        const requestId = this.dataset.id;
                        fetch(`/admin/borrow-requests/${requestId}/reject`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ reason: reason })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else {
                                alert(data.message || 'Error rejecting request');
                            }
                        });
                    }
                });
            });
        });
    </script>
</x-main-layout>
