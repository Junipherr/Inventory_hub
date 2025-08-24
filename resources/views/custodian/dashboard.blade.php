<x-main-layout>
    @push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <div class="page-content fade-in-up">
        <!-- Dashboard Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-gray-800">Custodian Dashboard</h1>
                        <p class="text-muted mb-0">Manage and monitor all items across your assigned rooms</p>
                    </div>
                    <div class="d-flex gap-2">
                        <div class="badge bg-primary fs-6 px-3 py-2">
                            <i class="fas fa-building mr-1"></i>
                            {{ $rooms->count() }} Rooms
                        </div>
                        <div class="badge bg-success fs-6 px-3 py-2">
                            <i class="fas fa-boxes mr-1"></i>
                            {{ $totalItems ?? 0 }} Items
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Selection Dropdown -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list mr-2"></i>
                                Room Selection
                            </h5>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="roomDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-door-open mr-2"></i>
                                    <span id="selectedRoomText">{{ $rooms->isNotEmpty() ? $rooms->first()->name : 'Select Room' }}</span>
                                    <span class="badge bg-primary ms-2" id="selectedRoomBadge">
                                        {{ $rooms->isNotEmpty() ? ($itemsByRoom[$rooms->first()->id]->count() ?? 0) : 0 }}
                                    </span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="roomDropdown">
                                    @foreach ($rooms as $room)
                                        @php
                                            $roomItems = $itemsByRoom[$room->id] ?? collect();
                                            $personInCharge = $personsInCharge[$room->id] ?? null;
                                            $totalQuantity = $roomItems->sum('quantity');
                                        @endphp
                                        <li>
                                            <a class="dropdown-item room-selector" 
                                               href="#" 
                                               data-room-id="{{ $room->id }}"
                                               data-room-name="{{ $room->name }}"
                                               data-person-in-charge="{{ $personInCharge ? $personInCharge->name : 'N/A' }}"
                                               data-total-quantity="{{ $totalQuantity }}">
                                                <i class="fas fa-door-open mr-2"></i>
                                                {{ $room->name }}
                                                <span class="badge bg-secondary ms-auto">
                                                    {{ $roomItems->count() }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Room Info Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded" id="roomInfoHeader">
                            @if($rooms->isNotEmpty())
                                @php
                                    $firstRoom = $rooms->first();
                                    $items = $itemsByRoom[$firstRoom->id] ?? collect();
                                    $personInCharge = $personsInCharge[$firstRoom->id] ?? null;
                                @endphp
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-user-tie text-primary mr-2"></i>
                                        Person in Charge: 
                                        <span class="text-primary" id="personInCharge">
                                            {{ $personInCharge ? $personInCharge->name : 'N/A' }}
                                        </span>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <span id="roomItemCount">{{ $items->count() }}</span> items in 
                                        <span id="currentRoomName">{{ $firstRoom->name }}</span>
                                    </small>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted">Total Quantity</small>
                                    <div class="h5 text-primary mb-0" id="totalQuantity">{{ $items->sum('quantity') }}</div>
                                </div>
                            @else
                                <div class="text-center w-100">
                                    <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                    <h6>No rooms available</h6>
                                </div>
                            @endif
                        </div>

                        <!-- Items Display Area -->
                        <div id="itemsDisplayArea">
                            @if($rooms->isNotEmpty())
                                @foreach ($rooms as $room)
                                    @php
                                        $items = $itemsByRoom[$room->id] ?? collect();
                                    @endphp
                                    <div class="row" id="itemsGrid-{{ $room->id }}" 
                                         style="{{ $loop->first ? 'display: flex;' : 'display: none;' }}">
                                        @if ($items->isEmpty())
                                            <div class="col-12">
                                                <div class="text-center py-5">
                                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">No items found</h5>
                                                    <p class="text-muted">This room currently has no items assigned.</p>
                                                </div>
                                            </div>
                                        @else
                                            @foreach ($items as $item)
                                                <div class="col-lg-4 col-md-6 mb-4 item-card">
                                                    <div class="card border-left-info shadow-sm h-100">
                                                        <div class="card-header bg-light py-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <h6 class="mb-0 font-weight-bold text-primary">
                                                                    {{ $item->item_name }}
                                                                </h6>
                                                                <span class="badge bg-info text-white">
                                                                    {{ $item->quantity }}x
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <p class="text-muted small mb-2">
                                                                <i class="fas fa-tag mr-1"></i>
                                                                {{ ucwords(str_replace('_', ' ', $item->category_id)) }}
                                                            </p>
                                                            <p class="text-muted small mb-2">
                                                                <i class="fas fa-map-marker-alt mr-1"></i>
                                                                {{ $room->name }}
                                                            </p>
                                                            <p class="text-muted small mb-3">
                                                                {{ Str::limit($item->description, 50) }}
                                                            </p>
                                                            
                                                            <div class="d-flex gap-1">
                                                                <button type="button" 
                                                                        class="btn btn-primary btn-sm flex-fill"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#itemInfoModal{{ $item->id }}">
                                                                    <i class="fas fa-eye mr-1"></i>
                                                                    Details
                                                                </button>
                                                                <button type="button" 
                                                                        class="btn btn-outline-secondary btn-sm"
                                                                        onclick="printItem({{ $item->id }})">
                                                                    <i class="fas fa-print"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">No rooms available</h5>
                                        <p class="text-muted">No rooms are currently assigned to you.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Details Modals -->
        @foreach ($rooms as $room)
            @php
                $items = $itemsByRoom[$room->id] ?? collect();
            @endphp
            @foreach ($items as $item)
                <div class="modal fade" id="itemInfoModal{{ $item->id }}" tabindex="-1"
                    aria-labelledby="itemInfoModalLabel{{ $item->id }}" aria-hidden="true"
                    data-qr="{{ $item->qr_code }}">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-gradient-primary text-white">
                                <h5 class="modal-title" id="itemInfoModalLabel{{ $item->id }}">
                                    <i class="fas fa-box mr-2"></i>
                                    {{ $item->item_name }}
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tbody>
                                                    <tr>
                                                        <td width="30%"><strong>Room:</strong></td>
                                                        <td>
                                                            <span class="badge bg-primary">{{ $room->name }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Person in Charge:</strong></td>
                                                        <td>
                                                            @php
                                                                $personInCharge = $personsInCharge[$room->id] ?? null;
                                                            @endphp
                                                            @if ($personInCharge)
                                                                <i class="fas fa-user-tie text-primary mr-1"></i>
                                                                {{ $personInCharge->name }}
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Category:</strong></td>
                                                        <td>{{ ucwords(str_replace('_', ' ', $item->category_id)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Quantity:</strong></td>
                                                        <td>
                                                            <span class="badge bg-success">{{ $item->quantity }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Description:</strong></td>
                                                        <td>{{ $item->description }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-center">
                                            <h6 class="text-muted mb-3">QR Code</h6>
                                            <div id="qrcode-container-{{ $item->id }}" class="d-flex justify-content-center">
 <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(128)->margin(2)->generate($item->qr_code ?? 'N/A')) }}" 
                                                     alt="QR Code for {{ $item->item_name }}" 
                                                     class="border rounded p-2"
                                                     style="width: 128px; height: 128px;">
                                            </div>
                                            <small class="text-muted d-block mt-2">
                                                Code: <code class="small">{{ $item->qr_code ?? 'N/A' }}</code>
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                @if ($item->units->isNotEmpty())
                                    <hr>
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-list mr-1"></i>
                                        Individual Units
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Unit #</th>
                                                    <th>Status</th>
                                                    <th>Last Checked</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->units as $unit)
                                                    <tr>
                                                        <td>{{ $unit->unit_number }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $unit->status == 'available' ? 'success' : 'warning' }}">
                                                                {{ ucfirst($unit->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            {{ $unit->last_checked_at ? $unit->last_checked_at->timezone(config('app.timezone'))->format('M d, Y H:i') : 'Never' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times mr-1"></i>
                                    Close
                                </button>
                                <button type="button" class="btn btn-primary" onclick="printItem({{ $item->id }})">
                                    <i class="fas fa-print mr-1"></i>
                                    Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>

    <!-- JavaScript for enhanced functionality -->
    <script>
        // Room selection functionality
        document.querySelectorAll('.room-selector').forEach(selector => {
            selector.addEventListener('click', function(e) {
                e.preventDefault();
                
                const roomId = this.getAttribute('data-room-id');
                const roomName = this.getAttribute('data-room-name');
                
                // Update dropdown button text and badge count
                document.getElementById('selectedRoomText').textContent = roomName;
                const roomItemsCount = this.querySelector('.badge').textContent;
                document.getElementById('selectedRoomBadge').textContent = roomItemsCount;
                
                // Update room info
                document.getElementById('currentRoomName').textContent = roomName;
                document.getElementById('personInCharge').textContent = this.getAttribute('data-person-in-charge') || 'N/A';
                
                // Update counts
                const roomItems = roomItemsCount;
                const totalQuantity = this.getAttribute('data-total-quantity') || 0;
                
                document.getElementById('roomItemCount').textContent = roomItems;
                document.getElementById('totalQuantity').textContent = totalQuantity;
                
                // Toggle items display grids
                document.querySelectorAll('[id^="itemsGrid-"]').forEach(grid => {
                    if (grid.id === `itemsGrid-${roomId}`) {
                        grid.style.display = 'flex';
                    } else {
                        grid.style.display = 'none';
                    }
                });
                
                // In a real implementation, you would load items via AJAX here
                console.log(`Loading items for room ${roomId}: ${roomName}`);
            });
        });

        // Print function
        function printItem(itemId) {
            const modal = document.getElementById(`itemInfoModal${itemId}`);
            const printContent = modal.querySelector('.modal-body').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Item Details</title>
                        <link href="{{ asset('assets/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
                    </head>
                    <body>
                        <div class="container mt-4">
                            <h2>Item Details</h2>
                            ${printContent}
                        </div>
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>

    <style>
        .card.border-left-primary {
            border-left: 0.25rem solid #4e73df;
        }
        .card.border-left-info {
            border-left: 0.25rem solid #36b9cc;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        }
        .item-card {
            transition: transform 0.2s;
        }
        .item-card:hover {
            transform: translateY(-2px);
        }
        .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }
        .dropdown-item.room-selector {
            color: #212529 !important;
            background-color: #f8f9fa !important;
        }
        .dropdown-item.room-selector:hover {
            background-color: rgba(78, 115, 223, 0.1) !important;
            color: #224abe !important;
        }
    </style>
</x-main-layout>
