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

        <!-- Request Details Header -->
        <div class="scanner-header">
            <div class="header-content">
                <h1 class="scanner-title">
                    <i class="fas fa-file-alt"></i> Borrow Request Details
                </h1>
                <div class="header-actions">
                    <a href="{{ route('admin.borrow-requests') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to All
                    </a>
                    @if($borrowRequest->status === 'pending')
                        <button type="button" class="btn btn-success" id="approve-btn">
                            <i class="fas fa-check"></i> Approve
                        </button>
                        <button type="button" class="btn btn-danger" id="reject-btn">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="scanner-content">
            <div class="row">
                <!-- Request Information -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle"></i> Request Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label>Request ID</label>
                                        <p class="text-primary font-weight-bold">#{{ $borrowRequest->id }}</p>
                                    </div>
                                    <div class="info-group">
                                        <label>User</label>
                                        <p>
                                            <strong>{{ $borrowRequest->user->name }}</strong><br>
                                            <small class="text-muted">{{ $borrowRequest->user->email }}</small>
                                        </p>
                                    </div>
                                    <div class="info-group">
                                        <label>Item</label>
                                        <p>
                                            <strong>{{ $borrowRequest->item->item_name }}</strong><br>
                                            <small class="text-muted">Room: {{ $borrowRequest->item->room->name ?? 'N/A' }}</small>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-group">
                                        <label>Quantity</label>
                                        <p>{{ $borrowRequest->quantity }} unit(s)</p>
                                    </div>
                                    <div class="info-group">
                                        <label>Expected Return Date</label>
                                        <p>{{ $borrowRequest->return_date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="info-group">
                                        <label>Status</label>
                                        <p>
                                            <span class="badge {{ $borrowRequest->getStatusBadgeClass() }}">
                                                {{ $borrowRequest->getStatusText() }}
                                            </span>
                                            @if($borrowRequest->isOverdue())
                                                <span class="badge badge-danger ml-2">Overdue</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="info-group">
                                <label>Purpose</label>
                                <p class="text-muted">{{ $borrowRequest->purpose }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock"></i> Request Timeline
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6>Request Submitted</h6>
                                        <p>{{ $borrowRequest->created_at->format('M d, Y H:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($borrowRequest->approved_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6>Request Approved</h6>
                                            <p>{{ $borrowRequest->approved_at->format('M d, Y H:i A') }}</p>
                                            @if($borrowRequest->admin_notes)
                                                <small class="text-muted">Notes: {{ $borrowRequest->admin_notes }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                @if($borrowRequest->returned_at)
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-info"></div>
                                        <div class="timeline-content">
                                            <h6>Item Returned</h6>
                                            <p>{{ $borrowRequest->returned_at->format('M d, Y H:i A') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Item Details -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-box"></i> Item Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="item-image">
                                @if($borrowRequest->item->image)
                                    <img src="{{ asset('storage/' . $borrowRequest->item->image) }}" 
                                         alt="{{ $borrowRequest->item->item_name }}" 
                                         class="img-fluid rounded">
                                @else
                                    <div class="placeholder-image">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="item-details mt-3">
                                <h6>{{ $borrowRequest->item->item_name }}</h6>
                                <p class="text-muted">{{ Str::limit($borrowRequest->item->description, 100) }}</p>
                                <div class="item-stats">
                                    <div class="stat">
                                        <label>Total Units:</label>
                                        <span>{{ $borrowRequest->item->units->count() }}</span>
                                    </div>
                                    <div class="stat">
                                        <label>Available Units:</label>
                                        <span>{{ $borrowRequest->item->units->where('status', '!=', 'Borrowed')->count() }}</span>
                                    </div>
                                    <div class="stat">
                                        <label>Room:</label>
                                        <span>{{ $borrowRequest->item->room->name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="stat">
                                        <label>Category:</label>
                                        <span>{{ ucwords(str_replace('_', ' ', $borrowRequest->item->category_id)) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    @if($borrowRequest->status === 'approved' && !$borrowRequest->returned_at)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-cogs"></i> Actions
                                </h5>
                            </div>
                            <div class="card-body">
                                <button type="button" class="btn btn-info w-100 mb-2" id="mark-returned">
                                    <i class="fas fa-undo"></i> Mark as Returned
                                </button>
                                <small class="text-muted">
                                    This will mark the item as returned and update inventory.
                                </small>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Approve request
            document.getElementById('approve-btn')?.addEventListener('click', function() {
                if (confirm('Are you sure you want to approve this borrow request?')) {
                    fetch('{{ route("admin.borrow-requests.approve", $borrowRequest->id) }}', {
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

            // Reject request
            document.getElementById('reject-btn')?.addEventListener('click', function() {
                const reason = prompt('Please provide a reason for rejection:');
                if (reason && reason.trim() !== '') {
                    fetch('{{ route("admin.borrow-requests.reject", $borrowRequest->id) }}', {
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

            // Mark as returned
            document.getElementById('mark-returned')?.addEventListener('click', function() {
                if (confirm('Are you sure you want to mark this item as returned?')) {
                    fetch('{{ route("admin.borrow-requests.mark-returned", $borrowRequest->id) }}', {
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
                            alert(data.message || 'Error marking as returned');
                        }
                    });
                }
            });
        });
    </script>
</x-main-layout>
