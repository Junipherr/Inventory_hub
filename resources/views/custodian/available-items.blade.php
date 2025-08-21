<x-main-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <div class="container-fluid py-4">
        <!-- Success/Error Notifications -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <strong>Success!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1 class="h3 mb-1 text-primary">
                                    <i class="fas fa-boxes me-2"></i>Available Items Inventory
                                </h1>
                                <p class="text-muted mb-0">Browse and manage items available for borrowing</p>
                            </div>
                            <div class="d-none d-md-block">
                                <span class="badge bg-primary fs-6">
                                    <i class="fas fa-list me-1"></i>{{ $summary['total_available_items'] }} Items
                                </span>
                            </div>
                        </div>
                        
                        <!-- Summary Statistics Cards -->
                        <div class="row mt-4">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card bg-primary bg-opacity-10 border-0">
                                    <div class="card-body text-center">
                                        <i class="fas fa-list fa-2x text-primary mb-2"></i>
                                        <h5 class="card-title mb-0">{{ $summary['total_available_items'] }}</h5>
                                        <small class="text-muted">Items Available</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card bg-success bg-opacity-10 border-0">
                                    <div class="card-body text-center">
                                        <i class="fas fa-hashtag fa-2x text-success mb-2"></i>
                                        <h5 class="card-title mb-0">{{ $summary['total_available_quantity'] }}</h5>
                                        <small class="text-muted">Total Units</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card bg-info bg-opacity-10 border-0">
                                    <div class="card-body text-center">
                                        <i class="fas fa-layer-group fa-2x text-info mb-2"></i>
                                        <h5 class="card-title mb-0">{{ $summary['categories']->count() }}</h5>
                                        <small class="text-muted">Categories</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card bg-warning bg-opacity-10 border-0">
                                    <div class="card-body text-center">
                                        <i class="fas fa-door-open fa-2x text-warning mb-2"></i>
                                        <h5 class="card-title mb-0">{{ $summary['rooms']->count() }}</h5>
                                        <small class="text-muted">Rooms</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="fas fa-filter me-2"></i>Filter & Search
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Search Items</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="searchItems" 
                                           placeholder="Search by name, description...">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Category</label>
                                <select class="form-select" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    @foreach($summary['categories'] as $category => $count)
                                        <option value="{{ $category }}">
                                            {{ ucwords(str_replace('_', ' ', $category)) }} ({{ $count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Room</label>
                                <select class="form-select" id="roomFilter">
                                    <option value="">All Rooms</option>
                                    @foreach($availableItems->groupBy('room.name') as $roomName => $items)
                                        <option value="{{ $roomName }}">
                                            {{ $roomName }} ({{ $items->count() }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mt-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Availability</label>
                                <select class="form-select" id="availabilityFilter">
                                    <option value="">All Availability</option>
                                    <option value="high">High (5+ available)</option>
                                    <option value="medium">Medium (2-4 available)</option>
                                    <option value="low">Low (1 available)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Sort By</label>
                                <select class="form-select" id="sortFilter">
                                    <option value="name">Name A-Z</option>
                                    <option value="quantity">Quantity (High to Low)</option>
                                    <option value="room">Room A-Z</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Items Grid -->
        <div class="row">
            <div class="col-12">
                @if($availableItems->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">No items available</h4>
                        <p class="text-muted">There are no items currently available for borrowing.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Back to Dashboard
                        </a>
                    </div>
                @else
                    <div class="row g-4" id="itemsContainer">
                        @foreach($availableItems as $item)
                            <div class="col-lg-4 col-md-6 item-card" 
                                 data-item-id="{{ $item->id }}"
                                 data-item-name="{{ strtolower($item->item_name) }}"
                                 data-room="{{ strtolower($item->room->name ?? 'n/a') }}"
                                 data-category="{{ strtolower($item->category_id) }}"
                                 data-available-qty="{{ $item->available_quantity }}">
                                
                                <div class="card h-100 shadow-sm border-0 hover-card">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-truncate">{{ $item->item_name }}</h6>
                                        <span class="badge bg-primary">{{ $item->available_quantity }} available</span>
                                    </div>
                                    
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                {{ $item->room->name ?? 'N/A' }}
                                            </small>
                                            <small class="text-muted d-block">
                                                <i class="fas fa-tag me-1"></i>
                                                {{ ucwords(str_replace('_', ' ', $item->category_id)) }}
                                            </small>
                                        </div>
                                        
                                        <p class="card-text small text-muted">
                                            {{ Str::limit($item->description, 100) }}
                                        </p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted">Total: {{ $item->total_quantity }}</small>
                                            </div>
                                            <div>
                                                @if($item->available_quantity > 5)
                                                    <span class="badge bg-success">High</span>
                                                @elseif($item->available_quantity > 1)
                                                    <span class="badge bg-warning">Medium</span>
                                                @else
                                                    <span class="badge bg-danger">Low</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer bg-transparent border-0">
                                        <a href="{{ route('inventory.items.show', $item->id) }}" 
                                           class="btn btn-outline-primary btn-sm w-100">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .hover-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
        }
        
        .item-card {
            transition: all 0.3s ease;
        }
        
        .item-card.hidden {
            display: none;
        }
        
        .badge {
            font-size: 0.75em;
        }
        
        .card-header {
            border-bottom: 1px solid rgba(0,0,0,0.125);
        }
        
        @media (max-width: 576px) {
            .card-header {
                font-size: 0.9rem;
            }
            
            .card-body {
                font-size: 0.875rem;
            }
        }
        
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>

    <!-- JavaScript for enhanced functionality -->
    <script>
        // Enhanced filtering and search
        function filterItems() {
            const searchTerm = document.getElementById('searchItems').value.toLowerCase();
            const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
            const roomFilter = document.getElementById('roomFilter').value.toLowerCase();
            const availabilityFilter = document.getElementById('availabilityFilter').value;
            const sortFilter = document.getElementById('sortFilter').value;
            
            const cards = document.querySelectorAll('.item-card');
            let visibleCards = [];
            
            cards.forEach(card => {
                const itemName = card.dataset.itemName;
                const room = card.dataset.room;
                const category = card.dataset.category;
                const availableQty = parseInt(card.dataset.availableQty) || 0;
                
                const matchesSearch = !searchTerm || itemName.includes(searchTerm) || room.includes(searchTerm);
                const matchesCategory = !categoryFilter || category === categoryFilter;
                const matchesRoom = !roomFilter || room === roomFilter;
                
                let matchesAvailability = true;
                if (availabilityFilter === 'high') {
                    matchesAvailability = availableQty > 5;
                } else if (availabilityFilter === 'medium') {
                    matchesAvailability = availableQty >= 2 && availableQty <= 4;
                } else if (availabilityFilter === 'low') {
                    matchesAvailability = availableQty === 1;
                }
                
                if (matchesSearch && matchesCategory && matchesRoom && matchesAvailability) {
                    card.classList.remove('hidden');
                    visibleCards.push(card);
                } else {
                    card.classList.add('hidden');
                }
            });
            
            // Sort visible cards
            visibleCards.sort((a, b) => {
                const aName = a.dataset.itemName;
                const bName = b.dataset.itemName;
                const aQty = parseInt(a.dataset.availableQty);
                const bQty = parseInt(b.dataset.availableQty);
                const aRoom = a.dataset.room;
                const bRoom = b.dataset.room;
                
                switch(sortFilter) {
                    case 'name':
                        return aName.localeCompare(bName);
                    case 'quantity':
                        return bQty - aQty;
                    case 'room':
                        return aRoom.localeCompare(bRoom);
                    default:
                        return 0;
                }
            });
            
            // Reorder DOM elements
            const container = document.getElementById('itemsContainer');
            visibleCards.forEach(card => container.appendChild(card));
        }

        // Add event listeners
        document.getElementById('searchItems').addEventListener('input', filterItems);
        document.getElementById('categoryFilter').addEventListener('change', filterItems);
        document.getElementById('roomFilter').addEventListener('change', filterItems);
        document.getElementById('availabilityFilter').addEventListener('change', filterItems);
        document.getElementById('sortFilter').addEventListener('change', filterItems);

        // Add click handlers for cards
        document.querySelectorAll('.hover-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't trigger if clicking on the button
                if (e.target.tagName !== 'A' && e.target.tagName !== 'BUTTON') {
                    const link = this.querySelector('a');
                    if (link) link.click();
                }
            });
        });
    </script>
</x-main-layout>
