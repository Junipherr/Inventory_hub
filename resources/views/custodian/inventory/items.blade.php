<x-main-layout>
    <!-- Font Awesome already loaded in layout -->
    
    <!-- Modern Flat CSS - No Gradients -->
    <style>
        /* Base Typography */
        .items-page {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .items-page h1, 
        .items-page h2, 
        .items-page h3 {
            font-weight: 600;
            color: #1a1a2e;
        }
        
        /* Form Inputs - Flat Design */
        .items-page .form-control,
        .items-page .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 0.625rem 0.875rem;
            font-size: 0.9rem;
            color: #333;
            background-color: #fff;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        
        .items-page .form-control:focus,
        .items-page .form-select:focus {
            border-color: #4a90d9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.1);
            outline: none;
        }
        
        .items-page .form-control::placeholder {
            color: #999;
        }
        
        /* Input Group */
        .items-page .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-right: none;
            color: #666;
        }
        
        .items-page .input-group .form-control {
            border-left: none;
        }
        
        /* Search Button - Flat Style */
        .items-page .btn-search {
            background-color: #4a90d9;
            color: #fff;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }
        
        .items-page .btn-search:hover {
            background-color: #3a7bc8;
        }
        
        /* Add New Button - Flat Style */
        .items-page .btn-add {
            background-color: #28a745;
            color: #fff;
            border: none;
            padding: 0.625rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            transition: background-color 0.2s ease;
        }
        
        .items-page .btn-add:hover {
            background-color: #218838;
        }
        
        /* Card - Flat Design */
        .items-page .card {
            background: #fff;
            border: 1px solid #e8e8e8;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        
        .items-page .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e8e8e8;
            padding: 1rem 1.25rem;
        }
        
        .items-page .card-body {
            padding: 0;
        }
        
        /* Table Styling */
        .items-page .table {
            margin-bottom: 0;
        }
        
        .items-page .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #e8e8e8;
            color: #555;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.875rem 1rem;
        }
        
        .items-page .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
            color: #333;
        }
        
        .items-page .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Striped rows handled by Bootstrap table-striped */
        
        /* Hover effect handled by Bootstrap table-hover */
        
        /* Table responsive wrapper */
        .items-page .table-responsive {
            border: none;
        }
        
        /* Badge Styling - Flat */
        .items-page .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: 4px;
        }
        
        .items-page .badge-item-count {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .items-page .badge-condition-good {
            background-color: #d4edda;
            color: #155724;
        }
        
        .items-page .badge-condition-fair {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .items-page .badge-condition-poor {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .items-page .badge-category {
            background-color: #e2e6ea;
            color: #495057;
        }
        
        /* Action Buttons - Flat */
        .items-page .btn-action {
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: opacity 0.2s ease;
            text-decoration: none;
        }
        
        .items-page .btn-action:hover {
            opacity: 0.85;
        }
        
        .items-page .btn-edit {
            background-color: #4a90d9;
            color: #fff;
        }
        
        .items-page .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }
        
        /* Empty State */
        .items-page .empty-state {
            padding: 3rem;
            text-align: center;
        }
        
        .items-page .empty-state i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
        }
        
        .items-page .empty-state h3 {
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .items-page .empty-state p {
            color: #999;
        }
        
        /* Pagination */
        .items-page .pagination-wrapper {
            padding: 1rem 1.25rem;
            background-color: #f8f9fa;
            border-top: 1px solid #e8e8e8;
        }
        
        .items-page .pagination {
            margin: 0;
        }
        
        .items-page .pagination .page-link {
            color: #333;
            border-color: #ddd;
            padding: 0.5rem 0.75rem;
        }
        
        .items-page .pagination .page-item.active .page-link {
            background-color: #4a90d9;
            border-color: #4a90d9;
        }
        
        .items-page .pagination .page-item.disabled .page-link {
            color: #999;
        }
        
        /* Modal Styling - Flat */
        .items-page .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e8e8e8;
            padding: 1rem 1.25rem;
        }
        
        .items-page .modal-title {
            color: #1a1a2e;
            font-weight: 600;
        }
        
        .items-page .modal-body {
            padding: 1.25rem;
        }
        
        .items-page .modal-footer {
            background-color: #f8f9fa;
            border-top: 1px solid #e8e8e8;
            padding: 1rem 1.25rem;
        }
        
        .items-page .btn-save {
            background-color: #4a90d9;
            color: #fff;
            border: none;
        }
        
        .items-page .btn-save:hover {
            background-color: #3a7bc8;
        }
        
        .items-page .btn-cancel {
            background-color: #6c757d;
            color: #fff;
            border: none;
        }
        
        .items-page .btn-cancel:hover {
            background-color: #5a6268;
        }
        
        .items-page .btn-confirm-delete {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }
        
        .items-page .btn-confirm-delete:hover {
            background-color: #c82333;
        }
        
        /* Alert */
        .items-page .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            border-radius: 6px;
        }
        
        /* Item icon box */
        .items-page .item-icon-box {
            width: 40px;
            height: 40px;
            background-color: #e3f2fd;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .items-page .item-icon-box i {
            color: #4a90d9;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .items-page .btn-search,
            .items-page .btn-add {
                width: 100%;
                margin-top: 0.5rem;
            }
            
            .items-page .table thead th,
            .items-page .table tbody td {
                padding: 0.625rem 0.75rem;
                font-size: 0.875rem;
            }
            
            .items-page .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }
        }
        
        /* Scrollbar styling */
        .items-page .table-responsive::-webkit-scrollbar {
            height: 6px;
        }
        
        .items-page .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .items-page .table-responsive::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 3px;
        }
        
        .items-page .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #aaa;
        }
    </style>

    <div class="items-page container-fluid py-4">
        <div class="container">
            <!-- Page Header -->
            <div class="mb-4">
                <div class="row mb-3">
                    <div class="col-12">
                        <h1 class="h3 mb-1">Inventory Items</h1>
                        <p class="text-muted mb-0">Manage and view all inventory items</p>
                    </div>
                </div>
                
                <!-- Search and Action Row -->
                <form method="GET" action="{{ route('inventory.items') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-6">
                        <label for="search" class="form-label visually-hidden">Search</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ request('search') }}" 
                                   placeholder="Search items..." 
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <button type="submit" class="btn btn-search w-100">
                            <i class="fas fa-search me-1"></i>
                            Search
                        </button>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="{{ route('inventory.create') }}" class="btn btn-add w-100">
                            <i class="fas fa-plus me-1"></i>
                            Add Item
                        </a>
                    </div>
                </form>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Items Card -->
            <div class="card">
                <!-- Card Header -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h2 class="h5 mb-0">All Items</h2>
                    <span class="badge badge-item-count">
                        {{ $items->total() }} {{ Str::plural('item', $items->total()) }}
                    </span>
                </div>

                <!-- Items Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Item Details</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Condition</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="item-icon-box me-3">
                                                    <i class="fas fa-box"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $item->item_name }}</div>
                                                    <div class="text-muted small">
                                                        {{ $item->description ? Str::limit($item->description, 50) : 'No description' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                            <span class="text-muted">
                                                {{ $item->room->name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-category">
                                                {{ $item->category_id }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $item->quantity }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $condition = $item->condition ?? 'Unknown';
                                                $badgeClass = match($condition) {
                                                    'Good' => 'badge-condition-good',
                                                    'Fair' => 'badge-condition-fair',
                                                    'Poor' => 'badge-condition-poor',
                                                    default => 'badge-category'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">
                                                {{ $condition }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 action-buttons">
                                                <button onclick="openEditModal(this)"
                                                        data-item-id="{{ $item->id }}"
                                                        data-item-name="{{ $item->item_name ?? '' }}"
                                                        data-description="{{ $item->description ?? '' }}"
                                                        data-quantity="{{ $item->quantity ?? 1 }}"
                                                        data-condition="{{ $item->condition ?? 'Good' }}"
                                                        data-room-id="{{ $item->room_id ?? '' }}"
                                                        data-category-id="{{ $item->category_id ?? '' }}"
                                                        data-purchase-date="{{ $item->purchase_date ?? '' }}"
                                                        data-warranty-expires="{{ $item->warranty_expires ?? '' }}"
                                                        class="btn btn-action btn-edit">
                                                    <i class="fas fa-edit me-1"></i>
                                                    Edit
                                                </button>
                                                <button onclick="openDeleteModal('{{ $item->id }}', '{{ $item->item_name ?? '' }}')"
                                                        class="btn btn-action btn-delete">
                                                    <i class="fas fa-trash me-1"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">
                                            <div class="empty-state">
                                                <i class="fas fa-inbox d-block"></i>
                                                <h3>No Items Found</h3>
                                                <p>Get started by adding your first inventory item</p>
                                                <a href="{{ route('inventory.create') }}" class="btn btn-add mt-2">
                                                    <i class="fas fa-plus me-1"></i>
                                                    Add First Item
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($items->hasPages())
                    <div class="pagination-wrapper">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div class="small text-muted">
                                Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results
                            </div>
                            <div>
                                {{ $items->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal fade" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" action="" class="p-3">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_item_name" class="form-label">Item Name</label>
                            <input type="text" name="item_name" id="edit_item_name" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="edit_quantity" min="1" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_condition" class="form-label">Condition</label>
                            <select name="condition" id="edit_condition" class="form-select">
                                <option value="Good">Good</option>
                                <option value="Fair">Fair</option>
                                <option value="Poor">Poor</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_room_id" class="form-label">Room Location</label>
                            <select name="room_id" id="edit_room_id" class="form-select">
                                <option value="">-- Select Room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">Category</label>
                        <select name="category_id" id="edit_category_id" class="form-select">
                            <option value="">-- Select Category --</option>
                            <option value="computer_hardware_peripherals">Computer Hardware & Peripherals</option>
                            <option value="office_classroom_furniture">Office and Classroom Furniture</option>
                            <option value="appliances_electronics">Appliances and Electronics</option>
                            <option value="classroom_office_supplies">Classroom/Office Supplies</option>
                            <option value="networking_equipment">Networking Equipment</option>
                            <option value="security_systems">Security Systems</option>
                            <option value="laboratory_equipment">Laboratory Equipment</option>
                            <option value="medical_equipment">Medical Equipment</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_purchase_date" class="form-label">Purchase Date</label>
                            <input type="date" name="purchase_date" id="edit_purchase_date" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_warranty_expires" class="form-label">Warranty Expires</label>
                            <input type="date" name="warranty_expires" id="edit_warranty_expires" class="form-control">
                        </div>
                    </div>
                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" class="btn btn-save flex-fill">
                            <i class="fas fa-save me-1"></i>
                            Save Changes
                        </button>
                        <button type="button" class="btn btn-cancel flex-fill" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal fade" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <p class="mb-3">
                        Are you sure you want to delete <strong id="deleteItemName"></strong>?
                    </p>
                    <p class="text-muted small">This action cannot be undone.</p>
                    <form id="deleteForm" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-confirm-delete flex-fill">
                                <i class="fas fa-trash me-1"></i>
                                Delete
                            </button>
                            <button type="button" class="btn btn-cancel flex-fill" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Modal functions using Bootstrap
        function openEditModal(button) {
            const itemId = button.dataset.itemId;
            const itemName = button.dataset.itemName;
            const description = button.dataset.description;
            const quantity = button.dataset.quantity;
            const condition = button.dataset.condition;
            const roomId = button.dataset.roomId;
            const categoryId = button.dataset.categoryId;
            const purchaseDate = button.dataset.purchaseDate;
            const warrantyExpires = button.dataset.warrantyExpires;

            const form = document.getElementById('editForm');
            form.action = `/inventory/items/${itemId}`;
            form.reset();

            document.getElementById('edit_item_name').value = itemName || '';
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_quantity').value = quantity || 1;
            document.getElementById('edit_condition').value = condition || 'Good';
            document.getElementById('edit_room_id').value = roomId || '';
            document.getElementById('edit_category_id').value = categoryId || '';

            // Handle dates
            const formatDate = (dateStr) => {
                if (!dateStr || dateStr.trim() === '') return '';
                if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) return dateStr;
                try {
                    const d = new Date(dateStr);
                    return isNaN(d.getTime()) ? dateStr : d.toISOString().split('T')[0];
                } catch (e) {
                    return dateStr;
                }
            };

            document.getElementById('edit_purchase_date').value = formatDate(purchaseDate);
            document.getElementById('edit_warranty_expires').value = formatDate(warrantyExpires);

            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }

        function closeEditModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            if (modal) modal.hide();
        }

        function openDeleteModal(itemId, itemName) {
            document.getElementById('deleteItemName').textContent = itemName;
            document.getElementById('deleteForm').action = `/inventory/items/${itemId}`;
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function closeDeleteModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            if (modal) modal.hide();
        }

        // Form handling
        function setupFormHandling(formId, successCallback) {
            const form = document.getElementById(formId);
            if (!form) return;

            const submitBtn = form.querySelector('button[type="submit"]');
            if (!submitBtn) return;

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';

                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        successCallback(data);
                    } else {
                        throw new Error(data.message || 'Operation failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    if (confirm('Would you like to submit normally? Error: ' + error.message)) {
                        form.removeEventListener('submit', arguments.callee);
                        form.submit();
                        return;
                    }
                    alert('Error: ' + error.message);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            });
        }

        // Setup handlers
        setupFormHandling('deleteForm', function(data) {
            closeDeleteModal();
            location.reload();
        });

        setupFormHandling('editForm', function(data) {
            closeEditModal();
            location.reload();
        });
    </script>
</x-main-layout>

