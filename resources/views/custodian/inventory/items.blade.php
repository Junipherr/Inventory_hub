<x-main-layout>
    <!-- Use Font Awesome CDN as in layout.blade.php -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Embedded important CSS from itemsdesign.css -->
    <style>
        .items-container {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
        }

        /* Enhanced Form Input Styling */
        .filter-inputs .form-control,
        .filter-inputs .form-select {
            border: 2px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 0.75rem 1rem !important;
            font-size: 0.95rem !important;
            color: #2d3748 !important;
            background-color: #ffffff !important;
            transition: all 0.2s ease !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05) !important;
        }

        .filter-inputs .form-control:focus,
        .filter-inputs .form-select:focus {
            border-color: #3498db !important;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1) !important;
            outline: none !important;
        }

        .filter-inputs .form-control:hover,
        .filter-inputs .form-select:hover {
            border-color: #a0aec0 !important;
        }

        /* Input group styling */
        .filter-inputs .input-group {
            position: relative !important;
        }

        .filter-inputs .input-group-text {
            background-color: #f7fafc !important;
            border: 2px solid #e2e8f0 !important;
            border-right: none !important;
            color: #4a5568 !important;
            padding: 0.75rem 1rem !important;
            border-radius: 8px 0 0 8px !important;
        }

        .filter-inputs .input-group .form-control {
            border-left: none !important;
            border-radius: 0 8px 8px 0 !important;
        }

        /* Label styling */
        .filter-inputs .form-label {
            font-weight: 600 !important;
            color: #2d3748 !important;
            margin-bottom: 0.5rem !important;
            font-size: 0.9rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
        }

        /* Select dropdown styling */
        .filter-inputs .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: right 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 16px 12px !important;
            padding-right: 2.5rem !important;
        }

        /* Text color improvements for better contrast */
        .filter-inputs ::placeholder {
            color: #6b7280 !important;
            opacity: 1 !important;
        }

        .filter-inputs :-ms-input-placeholder {
            color: #6b7280 !important;
        }

        .filter-inputs ::-ms-input-placeholder {
            color: #6b7280 !important;
        }

        /* Statistics Cards Text Contrast Improvement */
        .card.bg-primary.bg-opacity-10 .card-body,
        .card.bg-success.bg-opacity-10 .card-body,
        .card.bg-info.bg-opacity-10 .card-body,
        .card.bg-warning.bg-opacity-10 .card-body {
            color: #1f2937 !important;
        }

        .card.bg-primary.bg-opacity-10 .card-body h5,
        .card.bg-success.bg-opacity-10 .card-body h5,
        .card.bg-info.bg-opacity-10 .card-body h5,
        .card.bg-warning.bg-opacity-10 .card-body h5 {
            color: #1f2937 !important;
            font-weight: 700 !important;
        }

        .card.bg-primary.bg-opacity-10 .card-body small,
        .card.bg-success.bg-opacity-10 .card-body small,
        .card.bg-info.bg-opacity-10 .card-body small,
        .card.bg-warning.bg-opacity-10 .card-body small {
            color: #4b5563 !important;
            font-weight: 500 !important;
        }

        /* Item Cards Text Contrast */
        .item-card .card-body {
            color: #1f2937 !important;
        }

        .item-card .card-body .text-muted {
            color: #4b5563 !important;
        }

        .item-card .card-body small.text-muted {
            color: #4b5563 !important;
            font-weight: 500 !important;
        }

        /* Header Text Contrast */
        .text-primary {
            color: #1d4ed8 !important;
        }

        .text-muted {
            color: #4b5563 !important;
        }

        /* Ensure good contrast for all text elements */
        .card-title,
        .card-text,
        .form-label {
            color: #1f2937 !important;
        }

        /* Badge Styling with Better Contrast */
        .badge {
            font-weight: 600 !important;
        }

        .badge.bg-primary {
            background-color: #1d4ed8 !important;
            color: white !important;
        }

        .badge.bg-success {
            background-color: #059669 !important;
            color: white !important;
        }

        .badge.bg-warning {
            background-color: #d97706 !important;
            color: white !important;
        }

        .badge.bg-danger {
            background-color: #dc2626 !important;
            color: white !important;
        }

        .badge.bg-info {
            background-color: #0891b2 !important;
            color: white !important;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            .filter-inputs .form-control,
            .filter-inputs .form-select {
                border-color: #000 !important;
            }

            .filter-inputs .form-control:focus,
            .filter-inputs .form-select:focus {
                border-color: #1d4ed8 !important;
                outline: 2px solid #1d4ed8 !important;
            }

            .card.bg-primary.bg-opacity-10 .card-body,
            .card.bg-success.bg-opacity-10 .card-body,
            .card.bg-info.bg-opacity-10 .card-body,
            .card.bg-warning.bg-opacity-10 .card-body {
                color: #000 !important;
            }
        }

        /* Card styling for filter section */
        .filter-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%) !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
        }

        .filter-card .card-header {
            background: linear-gradient(135deg, #3498db 0%, #2c81ba 100%) !important;
            color: white !important;
            border: none !important;
            border-radius: 12px 12px 0 0 !important;
        }

        .filter-card .card-body {
            padding: 1.5rem !important;
        }

        /* Status badges */
        .status-badge {
            padding: 0.25rem 0.75rem !important;
            border-radius: 9999px !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
        }

        .status-good {
            background-color: #dcfce7 !important;
            color: #166534 !important;
        }

        .status-fair {
            background-color: #fef3c7 !important;
            color: #92400e !important;
        }

        .status-poor {
            background-color: #fee2e2 !important;
            color: #991b1b !important;
        }

        /* Action buttons */
        .action-btn {
            padding: 0.5rem 1rem !important;
            border-radius: 0.375rem !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.5rem !important;
        }

        .edit-btn {
            background-color: #3b82f6 !important;
            color: white !important;
        }

        .edit-btn:hover {
            background-color: #2563eb !important;
            transform: translateY(-1px) !important;
        }

        .delete-btn {
            background-color: #ef4444 !important;
            color: white !important;
        }

        .delete-btn:hover {
            background-color: #dc2626 !important;
            transform: translateY(-1px) !important;
        }

        /* Modal animations */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5) !important;
            backdrop-filter: blur(4px) !important;
        }

        .modal-content {
            animation: modalSlideIn 0.3s ease-out !important;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0 !important;
                transform: translateY(-50px) !important;
            }
            to {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .filter-inputs .form-control,
            .filter-inputs .form-select {
                padding: 0.625rem 0.875rem !important;
                font-size: 0.875rem !important;
            }

            .filter-inputs .input-group-text {
                padding: 0.625rem 0.875rem !important;
            }

            .filter-inputs .form-label {
                font-size: 0.8rem !important;
            }
        }

        /* Loading states */
        .loading {
            opacity: 0.6 !important;
            pointer-events: none !important;
        }

        /* Empty state styling */
        .empty-state {
            text-align: center !important;
            padding: 3rem !important;
        }

        .empty-state i {
            font-size: 3rem !important;
            color: #9ca3af !important;
            margin-bottom: 1rem !important;
        }

        .empty-state p {
            color: #6b7280 !important;
            margin-bottom: 0.5rem !important;
        }

        /* Pagination styling */
        .pagination {
            display: flex !important;
            justify-content: center !important;
            margin-top: 2rem !important;
        }

        .pagination a {
            padding: 0.5rem 1rem !important;
            margin: 0 0.25rem !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 0.375rem !important;
            text-decoration: none !important;
            color: #374151 !important;
            transition: all 0.2s ease !important;
        }

        .pagination a:hover {
            background-color: #f3f4f6 !important;
        }

        .pagination .active {
            background-color: #3b82f6 !important;
            color: white !important;
            border-color: #3b82f6 !important;
        }

        /* Card styling */
        .card {
            background: white !important;
            border-radius: 8px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
            overflow: hidden !important;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%) !important;
            padding: 1.5rem !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        .card-body {
            padding: 1.5rem !important;
        }

        /* Custom styles for enhanced design */
        .line-clamp-2 {
            display: -webkit-box !important;
            -webkit-line-clamp: 2 !important;
            -webkit-box-orient: vertical !important;
            overflow: hidden !important;
        }

        table {
            border-collapse: separate !important;
            border-spacing: 0 !important;
            width: 100% !important;
        }

        th {
            background: linear-gradient(to bottom, #f9fafb, #f3f4f6) !important;
        }

        .overflow-x-auto::-webkit-scrollbar {
            height: 6px !important;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1 !important;
            border-radius: 3px !important;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1 !important;
            border-radius: 3px !important;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8 !important;
        }

        /* Responsive table: horizontal scroll on mobile */
        @media (max-width: 640px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch !important;
                overflow-x: auto !important;
            }

            table, thead, tbody, th, td, tr {
                display: block !important;
                width: 100% !important;
            }

            thead tr {
                position: absolute !important;
                top: -9999px !important;
                left: -9999px !important;
            }

            tr {
                margin-bottom: 1rem !important;
                border-radius: 0.5rem !important;
                box-shadow: 0 1px 3px rgba(0,0,0,0.03) !important;
                background: #fff !important;
                padding: 0.5rem 0 !important;
            }

            td {
                border: none !important;
                position: relative !important;
                padding-left: 50% !important;
                min-height: 40px !important;
                font-size: 0.95rem !important;
            }

            td:before {
                position: absolute !important;
                top: 0.75rem !important;
                left: 1rem !important;
                width: 45% !important;
                white-space: nowrap !important;
                font-weight: 600 !important;
                color: #6b7280 !important;
                font-size: 0.85rem !important;
            }

            td:nth-of-type(1):before { content: "Item Details" !important; }
            td:nth-of-type(2):before { content: "Location" !important; }
            td:nth-of-type(3):before { content: "Category" !important; }
            td:nth-of-type(4):before { content: "Quantity" !important; }
            td:nth-of-type(5):before { content: "Condition" !important; }
            td:nth-of-type(6):before { content: "Actions" !important; }
        }

        /* Responsive modals */
        @media (max-width: 640px) {
            .modal-content {
                width: 95vw !important;
                max-width: 95vw !important;
                left: 2.5vw !important;
                right: 2.5vw !important;
                top: 10vw !important;
                padding: 1rem !important;
                transition: transform 0.3s ease, opacity 0.3s ease !important;
            }
        }

        /* Responsive buttons and spacing */
        @media (max-width: 640px) {
            .px-6 { padding-left: 1rem !important; padding-right: 1rem !important; }
            .py-4, .py-5, .py-8, .py-16 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
            .text-3xl { font-size: 1.5rem !important; }
            .text-xl { font-size: 1.1rem !important; }
            .rounded-lg, .rounded-xl { border-radius: 0.75rem !important; }
            .space-x-2 > :not([hidden]) ~ :not([hidden]) { margin-left: 0.5rem !important; }
            .flex-col { flex-direction: column !important; }
            .gap-4 { gap: 0.75rem !important; }
        }
    </style>
    
    <div class="container-fluid py-4">
        <div class="container">
            <!-- Header Section -->
            <div class="mb-4">
                <div class="row">
                    <div class="col-12">
                        <h1 class="h2 fw-bold text-dark">Inventory Management</h1>
                        <p class="text-muted small">Manage all inventory items with ease</p>
                    </div>
                </div>
                <!-- Search Form -->
                <form method="GET" action="{{ route('inventory.items') }}" class="row g-3">
                    <div class="col-12 col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search items by name, description, or category..."
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-search me-2"></i>
                            Search Items
                        </button>
                    </div>
                    <div class="col-12 col-md-3">
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-plus-circle me-2"></i>
                            Add New Item
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

            <!-- Content Card -->
            <div class="card shadow">
                <!-- Card Header -->
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0">All Inventory Items</h2>
                        <span class="badge bg-primary">
                            {{ $items->total() }} items
                        </span>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="overflow-x-auto">
                    <table class="table table-hover align-middle">
                        <thead class="table-light text-uppercase small">
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
                                            <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-2 me-3">
                                                <i class="fas fa-box text-primary fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold text-primary">{{ $item->item_name }}</div>
                                                <div class="text-muted small text-truncate" style="max-width: 250px;">
                                                    {{ $item->description ? Str::limit($item->description, 60) : 'No description' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                            <span class="small text-muted">
                                                {{ $item->room->name ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-wrap small">
                                            {{ $item->category_id }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark small px-3 py-1">
                                            {{ $item->quantity }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $conditionColors = [
                                                'Good' => ['bg' => 'bg-success', 'text' => 'text-white', 'icon' => 'fa-check-circle'],
                                                'Fair' => ['bg' => 'bg-warning', 'text' => 'text-dark', 'icon' => 'fa-exclamation-circle'],
                                                'Poor' => ['bg' => 'bg-danger', 'text' => 'text-white', 'icon' => 'fa-times-circle'],
                                            ];
                                            $condition = $item->condition ?? 'Unknown';
                                            $colors = $conditionColors[$condition] ?? ['bg' => 'bg-secondary', 'text' => 'text-white', 'icon' => 'fa-question-circle'];
                                        @endphp
                                        <span class="badge {{ $colors['bg'] }} {{ $colors['text'] }}">
                                            <i class="fas {{ $colors['icon'] }} me-1"></i>
                                            {{ $condition }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
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
                                                    class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                            <button onclick="openDeleteModal(this)"
                                                    data-item-id="{{ $item->id }}"
                                                    data-item-name="{{ $item->item_name ?? '' }}"
                                                    class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div>
                                            <div class="mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                                <i class="fas fa-inbox text-muted fs-2"></i>
                                            </div>
                                            <h3 class="h5 mb-2">No items found</h3>
                                            <p class="text-muted mb-4">Get started by adding your first inventory item</p>
                                            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-2"></i>
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
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} results
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
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Item Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" action="" class="p-3">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="edit_item_name" class="form-label">Item Name</label>
                        <input type="text" name="item_name" id="edit_item_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_quantity" class="form-label">Quantity</label>
                        <input type="number" name="quantity" id="edit_quantity" min="1" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="edit_condition" class="form-label">Condition</label>
                        <select name="condition" id="edit_condition" class="form-select">
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Poor">Poor</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_room_id" class="form-label">Room Location</label>
                        <select name="room_id" id="edit_room_id" class="form-select">
                            <option value="">-- Select Room --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_category_id" class="form-label">Category</label>
                        <select name="category_id" id="edit_category_id" class="form-select">
                            <option value="">-- Select Category --</option>
                            <option value="computer_hardware_peripherals">💻 Computer Hardware & Peripherals</option>
                            <option value="office_classroom_furniture">🪑 Office and Classroom Furniture</option>
                            <option value="appliances_electronics">📺 Appliances and Electronics</option>
                            <option value="classroom_office_supplies">📚 Classroom/Office Supplies</option>
                            <option value="networking_equipment">🌐 Networking Equipment</option>
                            <option value="security_systems">🔒 Security Systems</option>
                            <option value="laboratory_equipment">🧪 Laboratory Equipment</option>
                            <option value="medical_equipment">🏥 Medical Equipment</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_purchase_date" class="form-label">Purchase Date</label>
                        <input type="date" name="purchase_date" id="edit_purchase_date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="edit_warranty_expires" class="form-label">Warranty Expires</label>
                        <input type="date" name="warranty_expires" id="edit_warranty_expires" class="form-control">
                    </div>
                    <div class="d-flex gap-3 pt-3">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <span class="submit-text">Save Changes</span>
                            <span class="loading-text visually-hidden">Saving...</span>
                        </button>
                        <button type="button" onclick="closeEditModal()" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">
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
                <div class="modal-body text-center">
                    <div class="mx-auto bg-danger bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                        <i class="fas fa-exclamation-triangle text-danger fs-2"></i>
                    </div>
                    <p class="mb-4">
                        Are you sure you want to delete <span id="deleteItemName" class="fw-bold"></span>?
                        This action cannot be undone.
                    </p>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-danger flex-fill">
                                <span class="submit-text">Delete Item</span>
                                <span class="loading-text visually-hidden">Deleting...</span>
                            </button>
                            <button type="button" onclick="closeDeleteModal()" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">
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

            // Set the form action dynamically for the specific item
            form.action = `/inventory/items/${itemId}`;

            // Clear any previous values first
            form.reset();

            // Set new values with proper validation
            document.getElementById('edit_item_name').value = itemName || '';
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_quantity').value = quantity || 1;
            document.getElementById('edit_condition').value = condition || 'Good';
            document.getElementById('edit_room_id').value = roomId || '';
            document.getElementById('edit_category_id').value = categoryId || '';

            // Handle date fields - ensure they're in YYYY-MM-DD format for HTML date inputs
            if (purchaseDate && purchaseDate.trim() !== '') {
                try {
                    // If it's already in YYYY-MM-DD format, use it as-is
                    if (/^\d{4}-\d{2}-\d{2}$/.test(purchaseDate)) {
                        document.getElementById('edit_purchase_date').value = purchaseDate;
                    } else {
                        // Try to parse and format the date
                        const purchaseDateObj = new Date(purchaseDate);
                        if (!isNaN(purchaseDateObj.getTime())) {
                            document.getElementById('edit_purchase_date').value = purchaseDateObj.toISOString().split('T')[0];
                        } else {
                            document.getElementById('edit_purchase_date').value = purchaseDate;
                        }
                    }
                } catch (e) {
                    console.warn('Error parsing purchase date:', purchaseDate, e);
                    document.getElementById('edit_purchase_date').value = purchaseDate || '';
                }
            } else {
                document.getElementById('edit_purchase_date').value = '';
            }

            if (warrantyExpires && warrantyExpires.trim() !== '') {
                try {
                    // If it's already in YYYY-MM-DD format, use it as-is
                    if (/^\d{4}-\d{2}-\d{2}$/.test(warrantyExpires)) {
                        document.getElementById('edit_warranty_expires').value = warrantyExpires;
                    } else {
                        // Try to parse and format the date
                        const warrantyDateObj = new Date(warrantyExpires);
                        if (!isNaN(warrantyDateObj.getTime())) {
                            document.getElementById('edit_warranty_expires').value = warrantyDateObj.toISOString().split('T')[0];
                        } else {
                            document.getElementById('edit_warranty_expires').value = warrantyExpires;
                        }
                    }
                } catch (e) {
                    console.warn('Error parsing warranty date:', warrantyExpires, e);
                    document.getElementById('edit_warranty_expires').value = warrantyExpires || '';
                }
            } else {
                document.getElementById('edit_warranty_expires').value = '';
            }

            console.log('Opening edit modal for item:', itemId);
            console.log('Item data:', {
                itemName, description, quantity, condition, roomId, categoryId, purchaseDate, warrantyExpires
            });

            const modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        }

        function closeEditModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
            if (modal) {
                modal.hide();
            }
        }

        function openDeleteModal(itemId, itemName) {
            document.getElementById('deleteItemName').textContent = itemName;
            document.getElementById('deleteForm').action = `/inventory/items/${itemId}`;

            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        function closeDeleteModal() {
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            if (modal) {
                modal.hide();
            }
        }

        // Enhanced AJAX form handling with better UX
        function setupFormHandling(formId, successCallback) {
            const form = document.getElementById(formId);
            const submitBtn = form.querySelector('button[type="submit"]');
            const submitText = submitBtn.querySelector('.submit-text');
            const loadingText = submitBtn.querySelector('.loading-text');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Show loading state
                submitBtn.disabled = true;
                submitText.classList.add('visually-hidden');
                loadingText.classList.remove('visually-hidden');

                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                // Debug: Log form data
                console.log('Form action:', this.action);
                console.log('Form data:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }

                try {
                    const response = await fetch(this.action, {
                        method: 'POST', // Always use POST for form submissions
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });

                    console.log('Response status:', response.status);
                    const responseText = await response.text();
                    console.log('Response text:', responseText);

                    let data;
                    try {
                        data = JSON.parse(responseText);
                    } catch (parseError) {
                        console.error('JSON parse error:', parseError);
                        throw new Error('Invalid response format from server');
                    }

                    if (response.ok && data.success) {
                        successCallback(data);
                    } else {
                        throw new Error(data.message || 'Operation failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    // If AJAX fails, try submitting the form normally
                    if (confirm('AJAX submission failed. Would you like to submit the form normally? Error: ' + error.message)) {
                        // Remove the event listener temporarily and submit normally
                        form.removeEventListener('submit', arguments.callee);
                        form.submit();
                        return;
                    } else {
                        alert('Error: ' + error.message);
                    }
                } finally {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.classList.remove('visually-hidden');
                    loadingText.classList.add('visually-hidden');
                }
            });
        }

        // Setup form handlers
        setupFormHandling('deleteForm', function(data) {
            if (data.success) {
                closeDeleteModal();
                location.reload();
            }
        });

        setupFormHandling('editForm', function(data) {
            if (data.success) {
                closeEditModal();
                location.reload();
            }
        });

        // Initialize when DOM is ready
        document.addEventListener('DOMContentLoaded', function() {
            // Any additional initialization can go here
        });
    </script>

    <!-- Embedded CSS already above -->
</x-main-layout>
