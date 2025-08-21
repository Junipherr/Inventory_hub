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

        <!-- Error Notification -->
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Borrow Item Header -->
        <div class="scanner-header">
            <div class="header-content">
                <h1 class="scanner-title">
                    <i class="fas fa-hand-holding"></i> Borrow Item
                </h1>
                <div class="header-stats">
                    <span class="stat-item">
                        <i class="fas fa-list"></i> 
                        <span>{{ $availableItems->count() }}</span> Total Items
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-boxes"></i> 
                        <span>{{ $availableItems->sum('total_quantity') }}</span> Total Quantity
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-check-circle"></i> 
                        <span>{{ $availableItems->where('is_available', true)->count() }}</span> Available Items
                    </span>
                </div>
            </div>
        </div>

        <div class="scanner-content">
            <!-- Search Section -->
            <div class="items-section mb-4">
                <div class="items-header">
                    <h3><i class="fas fa-search"></i> All Items in Database</h3>
                    <div class="items-controls">
                        <div class="search-box">
                            <input type="text" class="form-control form-control-sm" id="searchItems" 
                                   placeholder="Search all items...">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                </div>

                @if($availableItems->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x"></i>
                        <h4>No items found</h4>
                        <p>There are no items in the database.</p>
                    </div>
                @else
                    <div class="items-table-container">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm" id="itemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Item</th>
                                        <th>Room</th>
                                        <th>Category</th>
                                        <th>Total Qty</th>
                                        <th>Available Qty</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availableItems as $item)
                                        <tr class="item-row" 
                                            data-item-id="{{ $item->id }}"
                                            data-item-name="{{ $item->item_name }}"
                                            data-room="{{ $item->room->name ?? 'N/A' }}"
                                            data-category="{{ ucwords(str_replace('_', ' ', $item->category_id)) }}"
                                            data-available-qty="{{ $item->available_quantity }}"
                                            data-total-qty="{{ $item->total_quantity }}">
                                            
                                            <td>
                                                <div class="item-info">
                                                    <strong class="d-block">{{ $item->item_name }}</strong>
                                                    <small class="text-muted">{{ Str::limit($item->description, 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $item->room->name ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $item->category_id)) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $item->total_quantity }}</span>
                                            </td>
                                            <td>
                                                @if($item->is_available)
                                                    <span class="badge bg-success">{{ $item->available_quantity }}</span>
                                                @else
                                                    <span class="badge bg-danger">0</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->is_available)
                                                    <span class="badge bg-success">Available</span>
                                                @else
                                                    <span class="badge bg-warning">Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-sm {{ $item->is_available ? 'btn-success' : 'btn-secondary' }} request-borrow"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#borrowRequestModal"
                                                        data-item-id="{{ $item->id }}"
                                                        data-item-name="{{ $item->item_name }}"
                                                        {{ !$item->is_available ? 'disabled' : '' }}>
                                                    <i class="fas fa-hand-holding"></i> Request
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

        <!-- Borrow Request Modal -->
        <div class="modal fade" id="borrowRequestModal" tabindex="-1" aria-labelledby="borrowRequestModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="borrowRequestModalLabel">
                            <i class="fas fa-hand-holding"></i> Borrow Request
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('viewer.borrow.submit') }}" id="borrowForm">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="item_name" class="form-label">Item Name</label>
                                <input type="text" class="form-control" id="item_name" readonly>
                                <input type="hidden" name="item_id" id="item_id">
                            </div>
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity to Borrow <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="quantity" name="quantity" 
                                       min="1" value="1" required>
                                <div class="form-text">Maximum quantity available will be validated.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="purpose" class="form-label">Purpose of Borrowing <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="purpose" name="purpose" rows="3" 
                                          placeholder="Please describe why you need to borrow this item..." required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="return_date" class="form-label">Expected Return Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="return_date" name="return_date" required>
                                <div class="form-text">Please select a date after today.</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Set minimum date for return date
        document.addEventListener('DOMContentLoaded', function() {
            const returnDateInput = document.getElementById('return_date');
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            returnDateInput.min = tomorrow.toISOString().split('T')[0];
        });

        // Handle borrow request modal
        const borrowButtons = document.querySelectorAll('.request-borrow');
        borrowButtons.forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.itemId;
                const itemName = this.dataset.itemName;
                const availableQty = parseInt(this.closest('tr').dataset.availableQty) || 0;
                
                document.getElementById('item_id').value = itemId;
                document.getElementById('item_name').value = itemName;
                
                // Set max quantity based on available quantity
                const quantityInput = document.getElementById('quantity');
                quantityInput.max = availableQty;
                quantityInput.value = Math.min(1, availableQty);
            });
        });

        // Search functionality
        document.getElementById('searchItems').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.item-row');
            
            rows.forEach(row => {
                const itemName = row.dataset.itemName.toLowerCase();
                const room = row.dataset.room.toLowerCase();
                const category = row.dataset.category.toLowerCase();
                
                if (itemName.includes(searchTerm) || room.includes(searchTerm) || category.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</x-main-layout>
