<x-main-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/css/viewer-borrow.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/viewer-responsive.css') }}" rel="stylesheet">
    
    <div class="borrow-wrapper">
        <!-- Enhanced Notifications -->
        <div id="notification-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <div>
                            <strong>Success!</strong> {{ session('success') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div>
                            <strong>Error!</strong> {{ session('error') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <!-- Enhanced Header with Quick Stats -->
        <div class="borrow-header">
            <div class="header-content">
                <h1>
                    <i class="fas fa-hand-holding"></i> Browse & Borrow Items
                    <span class="badge bg-light text-dark ms-2">{{ $availableItems->count() }} items</span>
                </h1>
                <p class="header-subtitle">Browse available items and submit borrow requests</p>
            </div>
            
            <div class="header-stats">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-boxes"></i></div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $availableItems->count() }}</div>
                        <div class="stat-label">Total Items</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $availableItems->where('is_available', true)->count() }}</div>
                        <div class="stat-label">Available</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <div class="stat-content">
                        <div class="stat-number">{{ $availableItems->where('is_available', false)->count() }}</div>
                        <div class="stat-label">Not Available</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Filter and Search Section -->
        <div class="search-section">
            <div class="search-header">
                <h3><i class="fas fa-search"></i> Advanced Search & Filter</h3>
                <div class="search-actions">
                    <button class="btn btn-outline-secondary" id="clearFilters">
                        <i class="fas fa-times"></i> Clear All
                    </button>
                </div>
            </div>
            
            <div class="filter-grid">
                <div class="filter-item">
                    <label for="searchItems">Search Items</label>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="search-input" id="searchItems" 
                               placeholder="Search by name, description, or category...">
                    </div>
                </div>
                
                <div class="filter-item">
                    <label for="categoryFilter">Category</label>
                    <select class="form-select" id="categoryFilter">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ ucwords(str_replace('_', ' ', $category)) }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-item">
                    <label for="roomFilter">Room</label>
                    <select class="form-select" id="roomFilter">
                        <option value="">All Rooms</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-item">
                    <label for="availabilityFilter">Availability</label>
                    <select class="form-select" id="availabilityFilter">
                        <option value="">All Items</option>
                        <option value="available">Available Only</option>
                        <option value="unavailable">Not Available</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Enhanced Results Section -->
        <div class="results-section">
            <div class="results-header">
                <h4>
                    <i class="fas fa-list"></i> Available Items
                    <span class="results-count">({{ $availableItems->count() }} total)</span>
                </h4>
                <div class="results-actions">
                    <button class="btn btn-outline-primary" id="exportBtn">
                        <i class="fas fa-download"></i> Export List
                    </button>
                </div>
            </div>

            @if($availableItems->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-inbox fa-3x"></i>
                    <h4>No items found</h4>
                    <p>There are no items available in the database.</p>
                    <button class="btn btn-primary mt-3" onclick="location.reload()">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            @else
                <div class="items-table-container">
                    <div class="table-responsive">
                        <table class="table table-hover" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th class="sortable" data-sort="item">Item <i class="fas fa-sort"></i></th>
                                    <th class="sortable" data-sort="room">Room <i class="fas fa-sort"></i></th>
                                    <th class="sortable" data-sort="category">Category <i class="fas fa-sort"></i></th>
                                    <th class="sortable" data-sort="total">Total Qty <i class="fas fa-sort"></i></th>
                                    <th class="sortable" data-sort="available">Available Qty <i class="fas fa-sort"></i></th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                @foreach($availableItems as $item)
                                    <tr class="item-row" 
                                        data-item-id="{{ $item->id }}"
                                        data-item-name="{{ $item->item_name }}"
                                        data-room="{{ $item->room->name ?? 'N/A' }}"
                                        data-category="{{ ucwords(str_replace('_', ' ', $item->category_id)) }}"
                                        data-available-qty="{{ $item->available_units }}"
                                        data-total-qty="{{ $item->total_units }}"
                                        data-is-available="{{ $item->is_available ? 'true' : 'false' }}">
                                        
                                        <td>
                                            <div class="item-info">
                                                <div class="item-image">
                                                    @if($item->image)
                                                        <img src="{{ asset('storage/' . $item->image) }}" 
                                                             alt="{{ $item->item_name }}" 
                                                             class="item-thumbnail">
                                                    @else
                                                        <div class="item-placeholder">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong class="item-name">{{ $item->item_name }}</strong>
                                                    <small class="item-description">{{ Str::limit($item->description, 50) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->room->name ?? 'N/A' }}</span>
                                        </td>
                                        
                                        <td>
                                            <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $item->category_id)) }}</span>
                                        </td>
                                        
                                        <td>
                                            <span class="badge bg-secondary">{{ $item->total_units }}</span>
                                        </td>
                                        
                                        <td>
                                            @if($item->is_available)
                                                <span class="badge bg-success">{{ $item->available_units }}</span>
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
                                            <div class="action-buttons">
                                                <button type="button" 
                                                        class="btn btn-sm {{ $item->is_available ? 'btn-success' : 'btn-secondary' }} request-borrow"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#borrowRequestModal"
                                                        data-item-id="{{ $item->id }}"
                                                        data-item-name="{{ $item->item_name }}"
                                                        {{ !$item->is_available ? 'disabled' : '' }}>
                                                    <i class="fas fa-hand-holding"></i> Request
                                                </button>
                                                
                                                @if(!$item->is_available)
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info"
                                                            onclick="showAvailabilityAlert('{{ $item->item_name }}')">
                                                        <i class="fas fa-bell"></i> Notify
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Enhanced Borrow Request Modal -->
        <div class="modal fade" id="borrowRequestModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-hand-holding"></i> Submit Borrow Request
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('viewer.borrow.submit') }}" id="borrowForm">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="item_name" class="form-label">Item Name</label>
                                        <input type="text" class="form-control" id="item_name" readonly>
                                        <input type="hidden" name="item_id" id="item_id">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="quantity" class="form-label">Quantity to Borrow <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="quantity" name="quantity" 
                                               min="1" max="10" value="1" required>
                                        <div class="form-text">Maximum available: <span id="maxQuantity"></span></div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="purpose" class="form-label">Purpose of Borrow <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="purpose" name="purpose" rows="3" 
                                                  placeholder="Please describe why you need to borrow this item..." required></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="expected_return_date" class="form-label">Expected Return Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="expected_return_date" name="expected_return_date" 
                                               min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" id="contact_number" name="contact_number" 
                                               placeholder="Your contact number" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Additional Notes</label>
                                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                                  placeholder="Any additional information..."></textarea>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Important:</strong> Please ensure all information is accurate before submitting your request.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBorrowRequest">
                                <i class="fas fa-paper-plane"></i> Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Enhanced JavaScript -->
    <script>
        // Initialize enhanced borrow functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize search and filter functionality
            initializeSearchAndFilter();
            
            // Initialize modal functionality
            initializeBorrowModal();
            
            // Initialize table sorting
            initializeTableSorting();
            
            // Initialize export functionality
            initializeExportFunctionality();
        });
        
        function initializeSearchAndFilter() {
            const searchInput = document.getElementById('searchItems');
            const categoryFilter = document.getElementById('categoryFilter');
            const roomFilter = document.getElementById('roomFilter');
            const availabilityFilter = document.getElementById('availabilityFilter');
            const clearFiltersBtn = document.getElementById('clearFilters');
            
            // Add event listeners for real-time filtering
            [searchInput, categoryFilter, roomFilter, availabilityFilter].forEach(element => {
                element.addEventListener('input', filterItems);
            });
            
            clearFiltersBtn.addEventListener('click', clearAllFilters);
        }
        
        function filterItems() {
            const searchTerm = document.getElementById('searchItems').value.toLowerCase();
            const category = document.getElementById('categoryFilter').value;
            const room = document.getElementById('roomFilter').value;
            const availability = document.getElementById('availabilityFilter').value;
            
            const rows = document.querySelectorAll('.item-row');
            
            rows.forEach(row => {
                const itemName = row.dataset.itemName.toLowerCase();
                const itemCategory = row.dataset.category.toLowerCase();
                const itemRoom = row.dataset.room.toLowerCase();
                const isAvailable = row.dataset.isAvailable;
                
                let showRow = true;
                
                // Search filter
                if (searchTerm && !itemName.includes(searchTerm) && !itemCategory.includes(searchTerm)) {
                    showRow = false;
                }
                
                // Category filter
                if (category && itemCategory !== category.toLowerCase()) {
                    showRow = false;
                }
                
                // Room filter
                if (room && row.dataset.roomId !== room) {
                    showRow = false;
                }
                
                // Availability filter
                if (availability === 'available' && isAvailable !== 'true') {
                    showRow = false;
                }
                if (availability === 'unavailable' && isAvailable !== 'false') {
                    showRow = false;
                }
                
                row.style.display = showRow ? '' : 'none';
            });
            
            updateResultsCount();
        }
        
        function clearAllFilters() {
            document.getElementById('searchItems').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('roomFilter').value = '';
            document.getElementById('availabilityFilter').value = '';
            
            const rows = document.querySelectorAll('.item-row');
            rows.forEach(row => {
                row.style.display = '';
            });
            
            updateResultsCount();
        }
        
        function updateResultsCount() {
            const visibleRows = document.querySelectorAll('.item-row[style="display: none"]').length;
            const totalRows = document.querySelectorAll('.item-row').length;
            const visibleCount = totalRows - visibleRows;
            
            document.querySelector('.results-count').textContent = `(${visibleCount} of ${totalRows} shown)`;
        }
        
        function initializeBorrowModal() {
            const modal = document.getElementById('borrowRequestModal');
            const form = document.getElementById('borrowForm');
            
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const itemId = button.dataset.itemId;
                const itemName = button.dataset.itemName;
                
                // Set form values
                document.getElementById('item_id').value = itemId;
                document.getElementById('item_name').value = itemName;
                
                // Update max quantity based on available units
                const row = button.closest('.item-row');
                const maxQty = parseInt(row.dataset.availableQty);
                document.getElementById('maxQuantity').textContent = maxQty;
                document.getElementById('quantity').max = maxQty;
            });
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Add loading state
                const submitBtn = document.getElementById('submitBorrowRequest');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                
                // Submit form via AJAX
                const formData = new FormData(form);
                
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showNotification('success', 'Borrow request submitted successfully!');
                        
                        // Close modal
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        modalInstance.hide();
                        
                        // Reset form
                        form.reset();
                    } else {
                        showNotification('error', data.message || 'An error occurred. Please try again.');
                    }
                })
                .catch(error => {
                    showNotification('error', 'An error occurred. Please try again.');
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Request';
                });
            });
        }
        
        function initializeTableSorting() {
            const headers = document.querySelectorAll('.sortable');
            headers.forEach(header => {
                header.addEventListener('click', function() {
                    const sortBy = this.dataset.sort;
                    sortTable(sortBy);
                });
            });
        }
        
        function sortTable(sortBy) {
            const tbody = document.getElementById('itemsTableBody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort((a, b) => {
                let valA, valB;
                
                switch(sortBy) {
                    case 'item':
                        valA = a.dataset.itemName;
                        valB = b.dataset.itemName;
                        break;
                    case 'room':
                        valA = a.dataset.room;
                        valB = b.dataset.room;
                        break;
                    case 'category':
                        valA = a.dataset.category;
                        valB = b.dataset.category;
                        break;
                    case 'total':
                        valA = parseInt(a.dataset.totalQty);
                        valB = parseInt(b.dataset.totalQty);
                        break;
                    case 'available':
                        valA = parseInt(a.dataset.availableQty);
                        valB = parseInt(b.dataset.availableQty);
                        break;
                }
                
                return valA.localeCompare ? valA.localeCompare(valB) : valA - valB;
            });
            
            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        }
        
        function initializeExportFunctionality() {
            const exportBtn = document.getElementById('exportBtn');
            exportBtn.addEventListener('click', exportItemsList);
        }
        
        function exportItemsList() {
            const visibleRows = document.querySelectorAll('.item-row:not([style*="display: none"])');
            const items = [];
            
            visibleRows.forEach(row => {
                items.push({
                    name: row.dataset.itemName,
                    room: row.dataset.room,
                    category: row.dataset.category,
                    total: row.dataset.totalQty,
                    available: row.dataset.availableQty,
                    status: row.dataset.isAvailable === 'true' ? 'Available' : 'Not Available'
                });
            });
            
            // Create CSV content
            let csvContent = 'Item Name,Room,Category,Total Quantity,Available Quantity,Status\n';
            items.forEach(item => {
                csvContent += `"${item.name}","${item.room}","${item.category}",${item.total},${item.available},"${item.status}"\n`;
            });
            
            // Download file
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'available-items.csv';
            a.click();
            window.URL.revokeObjectURL(url);
        }
        
        function showNotification(type, message) {
            const container = document.getElementById('notification-container');
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            
            const notification = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas ${icon} me-2"></i>
                        <div>${message}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            container.insertAdjacentHTML('afterbegin', notification);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const alert = container.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }
        
        function showAvailabilityAlert(itemName) {
            showNotification('info', `You will be notified when ${itemName} becomes available.`);
        }
    </script>
</x-main-layout>
                                    
