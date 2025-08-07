<x-main-layout>
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

        <!-- Quick Stats Cards -->
        <div class="row mb-4">
            @foreach ($rooms as $room)
                @php
                    $roomItems = $itemsByRoom[$room->id] ?? collect();
                    $totalQuantity = $roomItems->sum('quantity');
                @endphp
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        {{ $room->name }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $roomItems->count() }} Items
                                    </div>
                                    <div class="text-xs text-muted">
                                        {{ $totalQuantity }} total quantity
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-door-open fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Main Content Area -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list mr-2"></i>
                                Items by Room
                            </h5>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-light btn-sm" id="gridViewBtn">
                                    <i class="fas fa-th"></i>
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm active" id="listViewBtn">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Room Navigation -->
                        <ul class="nav nav-pills nav-fill mb-4" id="roomTabs" role="tablist">
                            @foreach ($rooms as $index => $room)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link @if($index == 0) active @endif" 
                                            id="room-{{ $room->id }}-tab" 
                                            data-bs-toggle="pill" 
                                            data-bs-target="#room-{{ $room->id }}" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="room-{{ $room->id }}" 
                                            aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                        <i class="fas fa-door-open mr-2"></i>
                                        {{ $room->name }}
                                        <span class="badge bg-secondary ms-2">
                                            {{ $itemsByRoom[$room->id]->count() ?? 0 }}
                                        </span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content" id="roomTabsContent">
                            @foreach ($rooms as $index => $room)
                                <div class="tab-pane fade @if($index == 0) show active @endif" 
                                     id="room-{{ $room->id }}" 
                                     role="tabpanel" 
                                     aria-labelledby="room-{{ $room->id }}-tab">
                                    
                                    @php
                                        $items = $itemsByRoom[$room->id] ?? collect();
                                        $personInCharge = $personsInCharge[$room->id] ?? null;
                                    @endphp

                                    <!-- Room Info Header -->
                                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded">
                                        <div>
                                            <h6 class="mb-1">
                                                <i class="fas fa-user-tie text-primary mr-2"></i>
                                                Person in Charge: 
                                                <span class="text-primary">
                                                    {{ $personInCharge ? $personInCharge->name : 'N/A' }}
                                                </span>
                                            </h6>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle mr-1"></i>
                                                {{ $items->count() }} items in this room
                                            </small>
                                        </div>
                                        <div class="text-right">
                                            <small class="text-muted">Total Quantity</small>
                                            <div class="h5 text-primary mb-0">{{ $items->sum('quantity') }}</div>
                                        </div>
                                    </div>

                                    <!-- Items Grid/List -->
                                    <div class="items-container">
                                        @if ($items->isEmpty())
                                            <div class="text-center py-5">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <h5 class="text-muted">No items found</h5>
                                                <p class="text-muted">This room currently has no items assigned.</p>
                                            </div>
                                        @else
                                            <div class="row" id="itemsGrid-{{ $room->id }}">
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
                                                                
                                                                <!-- Quick Actions -->
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
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
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
                                            <canvas id="qrcode-{{ $item->id }}" class="border rounded p-2"></canvas>
                                            <small class="text-muted d-block mt-2">
                                                Scan for quick access
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
                                                        <td>
                                                            <i class="fas fa-hashtag mr-1"></i>
                                                            {{ $unit->unit_number }}
                                                        </td>
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
        // View toggle functionality
        document.getElementById('gridViewBtn')?.addEventListener('click', function() {
            document.querySelectorAll('[id^="itemsGrid-"]').forEach(grid => {
                grid.className = 'row row-cols-1 row-cols-md-2 row-cols-lg-3';
            });
            this.classList.add('active');
            document.getElementById('listViewBtn').classList.remove('active');
        });

        document.getElementById('listViewBtn')?.addEventListener('click', function() {
            document.querySelectorAll('[id^="itemsGrid-"]').forEach(grid => {
                grid.className = 'row';
            });
            this.classList.add('active');
            document.getElementById('gridViewBtn').classList.remove('active');
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

        // Generate QR codes
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-qr]').forEach(element => {
                const qrCode = element.getAttribute('data-qr');
                const canvasId = element.querySelector('canvas').id;
                if (qrCode && window.QRCode) {
                    QRCode.toCanvas(document.getElementById(canvasId), qrCode, function (error) {
                        if (error) console.error(error);
                    });
                }
            });
        });
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
        
        .nav-pills .nav-link {
            transition: all 0.3s;
        }
        
        .nav-pills .nav-link:hover {
            background-color: rgba(78, 115, 223, 0.1);
        }
        
        .nav-pills .nav-link.active {
            background-color: #4e73df;
        }
    </style>
</x-main-layout>
