<x-main-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <div class="scanner-container">
        <!-- Success Notification -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Borrow History Header -->
        <div class="scanner-header">
            <div class="header-content">
                <h1 class="scanner-title">
                    <i class="fas fa-history"></i> Borrow History
                </h1>
                <div class="header-stats">
                    <span class="stat-item">
                        <i class="fas fa-list"></i> 
                        <span>{{ $borrowRequests->count() }}</span> Total Requests
                    </span>
                </div>
            </div>
        </div>

        <div class="scanner-content">
            <!-- Filter Section -->
            <div class="items-section mb-4">
                <div class="items-header">
                    <h3><i class="fas fa-filter"></i> Your Borrow Requests</h3>
                    <div class="items-controls">
                        <div class="search-box">
                            <input type="text" class="form-control form-control-sm" id="searchRequests" 
                                   placeholder="Search requests...">
                            <i class="fas fa-search"></i>
                        </div>
                        <select class="form-select form-select-sm" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="returned">Returned</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                </div>

                @if($borrowRequests->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x"></i>
                        <h4>No borrow requests</h4>
                        <p>You haven't made any borrow requests yet.</p>
                        <a href="{{ route('viewer.borrow') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-hand-holding"></i> Browse Items
                        </a>
                    </div>
                @else
                    <div class="items-table-container">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm" id="requestsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Room</th>
                                        <th>Quantity</th>
                                        <th>Purpose</th>
                                        <th>Return Date</th>
                                        <th>Status</th>
                                        <th>Requested</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowRequests as $request)
                                        <tr class="request-row" 
                                            data-item-name="{{ $request->item->item_name }}"
                                            data-status="{{ $request->status }}">
                                            
                                            <td>
                                                <div class="item-info">
                                                    <strong class="d-block">{{ $request->item->item_name }}</strong>
                                                    <small class="text-muted">{{ Str::limit($request->item->description, 30) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $request->item->room->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ $request->quantity }}</span>
                                            </td>
                                            <td>
                                                <small>{{ Str::limit($request->purpose, 50) }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $request->return_date->format('M d, Y') }}</span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $request->getStatusBadgeClass() }}">
                                                    {{ $request->getStatusText() }}
                                                </span>
                                                @if($request->isOverdue())
                                                    <span class="badge bg-danger ms-1">Overdue</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $request->created_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-primary view-details"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#requestDetailsModal"
                                                        data-request-id="{{ $request->id }}"
                                                        data-item-name="{{ $request->item->item_name }}"
                                                        data-quantity="{{ $request->quantity }}"
                                                        data-purpose="{{ $request->purpose }}"
                                                        data-return-date="{{ $request->return_date->format('Y-m-d') }}"
                                                        data-status="{{ $request->status }}"
                                                        data-admin-notes="{{ $request->admin_notes ?? 'No notes provided' }}"
                                                        data-requested-date="{{ $request->created_at->format('M d, Y H:i') }}">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Request Details Modal -->
        <div class="modal fade" id="requestDetailsModal" tabindex="-1" aria-labelledby="requestDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestDetailsModalLabel">
                            <i class="fas fa-file-alt"></i> Request Details
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label>Item Name:</label>
                                    <span id="modalItemName"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Quantity Requested:</label>
                                    <span id="modalQuantity"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Return Date:</label>
                                    <span id="modalReturnDate"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label>Status:</label>
                                    <span id="modalStatus"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Requested Date:</label>
                                    <span id="modalRequestedDate"></span>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label>Purpose:</label>
                            <p id="modalPurpose"></p>
                        </div>
                        <div class="detail-item">
                            <label>Admin Notes:</label>
                            <p id="modalAdminNotes"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Search and filter functionality
        document.getElementById('searchRequests').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.request-row');
            
            rows.forEach(row => {
                const itemName = row.dataset.itemName.toLowerCase();
                
                if (itemName.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        document.getElementById('statusFilter').addEventListener('change', function() {
            const statusFilter = this.value.toLowerCase();
            const rows = document.querySelectorAll('.request-row');
            
            rows.forEach(row => {
                const status = row.dataset.status.toLowerCase();
                
                if (!statusFilter || status === statusFilter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Handle request details modal
        const viewButtons = document.querySelectorAll('.view-details');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const itemName = this.dataset.itemName;
                const quantity = this.dataset.quantity;
                const purpose = this.dataset.purpose;
                const returnDate = this.dataset.returnDate;
                const status = this.dataset.status;
                const adminNotes = this.dataset.adminNotes;
                const requestedDate = this.dataset.requestedDate;
                
                document.getElementById('modalItemName').textContent = itemName;
                document.getElementById('modalQuantity').textContent = quantity;
                document.getElementById('modalPurpose').textContent = purpose;
                document.getElementById('modalReturnDate').textContent = new Date(returnDate).toLocaleDateString();
                document.getElementById('modalStatus').textContent = status.charAt(0).toUpperCase() + status.slice(1);
                document.getElementById('modalAdminNotes').textContent = adminNotes;
                document.getElementById('modalRequestedDate').textContent = requestedDate;
            });
        });
    </script>
</x-main-layout>
