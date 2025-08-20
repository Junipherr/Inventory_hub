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

        <!-- Pending Requests Header -->
        <div class="scanner-header">
            <div class="header-content">
                <h1 class="scanner-title">
                    <i class="fas fa-clock"></i> Pending Borrow Requests
                </h1>
                <div class="header-stats">
                    <span class="stat-item">
                        <i class="fas fa-list"></i> 
                        <span>{{ $pendingRequests->count() }}</span> Pending
                    </span>
                    <a href="{{ route('admin.borrow-requests') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to All
                    </a>
                </div>
            </div>
        </div>

        <div class="scanner-content">
            <!-- Quick Actions -->
            <div class="items-section mb-4">
                <div class="items-header">
                    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    <div class="items-controls">
                        <button type="button" class="btn btn-success btn-sm" id="approve-all">
                            <i class="fas fa-check-double"></i> Approve All Selected
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" id="reject-all">
                            <i class="fas fa-times"></i> Reject All Selected
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pending Requests Cards -->
            @if($pendingRequests->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox fa-3x"></i>
                    <h4>No pending requests</h4>
                    <p>Great! All borrow requests have been processed.</p>
                </div>
            @else
                <div class="row">
                    @foreach($pendingRequests as $request)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card request-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-hand-holding"></i> 
                                        Request #{{ $request->id }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="request-info">
                                        <div class="info-item">
                                            <strong>User:</strong>
                                            <span>{{ $request->user->name }}</span>
                                            <small class="text-muted">{{ $request->user->email }}</small>
                                        </div>
                                        <div class="info-item">
                                            <strong>Item:</strong>
                                            <span>{{ $request->item->item_name }}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong>Quantity:</strong>
                                            <span>{{ $request->quantity }}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong>Return Date:</strong>
                                            <span>{{ $request->return_date->format('M d, Y') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <strong>Purpose:</strong>
                                            <p class="text-muted">{{ Str::limit($request->purpose, 100) }}</p>
                                        </div>
                                        <div class="info-item">
                                            <strong>Requested:</strong>
                                            <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="btn-group btn-group-sm w-100">
                                        <button type="button" 
                                                class="btn btn-success approve-request" 
                                                data-id="{{ $request->id }}">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button type="button" 
                                                class="btn btn-danger reject-request" 
                                                data-id="{{ $request->id }}">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                        <a href="{{ route('admin.borrow-requests.show', $request->id) }}" 
                                           class="btn btn-info">
                                            <i class="fas fa-eye"></i> Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $pendingRequests->links() }}
                </div>
            @endif
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
