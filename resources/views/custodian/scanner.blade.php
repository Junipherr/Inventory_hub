@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp
<x-main-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/css/scannerblade.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/scannerblade-responsive.css') }}" rel="stylesheet">
    
    <div class="scanner-container">
        <!-- Success Notification -->
        <div id="dynamicSuccessMessage"
            style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <span id="successMessageText"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <!-- Scanner Header -->
        <div class="scanner-header">
            <div class="header-content">
                <div>
                    <h1 class="scanner-title mb-2 mb-md-0">
                        <i class="fas fa-qrcode me-2"></i>QR Scanner & Inventory Manager
                    </h1>
                </div>
                <div class="header-stats">
                    <span class="stat-item">
                        <i class="fas fa-list"></i> 
                        <span id="totalItems">{{ $items->total() }}</span> Items
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-check-circle"></i>
                        <span id="scannedItems">0</span> Scanned
                    </span>
                </div>
            </div>
        </div>

        <div class="scanner-content">
            <!-- Scanner Section -->
            <div class="scanner-section">
                <!-- Scanner Card -->
                <div class="card scanner-card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-camera me-2"></i>QR Scanner</h5>
                            <div class="scanner-controls">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="startScanner">
                                    <i class="fas fa-play me-1"></i> Start Camera
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" id="stopScanner" style="display: none;">
                                    <i class="fas fa-stop me-1"></i> Stop Camera
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="scanner-viewport">
                            <video id="scannerVideo" autoplay muted playsinline style="display: none;"></video>
                            <canvas id="scannerCanvas" style="display: none;"></canvas>
                            <div id="scannerPlaceholder" class="scanner-placeholder">
                                <i class="fas fa-camera fa-3x mb-3 d-block"></i>
                                <p class="mb-0">Click "Start Camera" to begin scanning QR codes</p>
                            </div>
                        </div>

                        <div class="scanner-results px-3 py-3">
                            <div id="scanResult" class="scan-result" style="display: none;">
                                <h5 class="mb-2">Last Scanned:</h5>
                                <p id="scannedCode" class="mb-2 fw-bold"></p>
                                <button type="button" class="btn btn-success btn-sm" id="findItem">
                                    <i class="fas fa-search me-1"></i> Find Item
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manual QR Input Card -->
                <div class="card manual-input-card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3"><i class="fas fa-keyboard me-2"></i>Manual QR Input</h5>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" id="manualQrInput" 
                                   placeholder="Enter QR code manually">
                            <button class="btn btn-primary btn-sm" type="button" id="manualScanBtn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items List Section -->
            <div class="items-section">
                <div class="items-header">
                    <h3><i class="fas fa-list me-2"></i>Inventory Items</h3>
                    <div class="items-controls">
                        <div class="search-box">
                            <input type="text" class="form-control form-control-sm" id="searchItems" 
                                   placeholder="Search items...">
                            <i class="fas fa-search"></i>
                        </div>
                        <button type="button" class="btn btn-outline-info btn-sm" id="showLegendBtn">
                            <i class="fas fa-info-circle me-1"></i> Legend
                        </button>
                    </div>
                </div>

                @if ($items->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                        <h4>No items found</h4>
                        <p>Add some items to start scanning</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('scanner.update') }}" id="scannerForm">
                        @csrf
                        <div class="items-table-container">
                            <div class="table-responsive">
                                <table class="table table-hover table-sm" id="itemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="d-none d-md-table-cell">Item</th>
                                            <th class="d-table-cell d-md-none">Item</th>
                                            <th class="d-none d-sm-table-cell">Room</th>
                                            <th class="d-none d-sm-table-cell">Category</th>
                                            <th class="d-none d-sm-table-cell">Qty</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            @foreach ($item->units as $unit)
                                                <tr class="item-row" 
                                                    data-item-id="{{ $item->id }}"
                                                    data-unit-id="{{ $unit->id }}"
                                                    data-qr-code="{{ $unit->qr_code ?? '' }}"
                                                    data-item-name="{{ $item->item_name }}"
                                                    data-room="{{ $item->room->name ?? 'N/A' }}"
                                                    data-category="{{ ucwords(str_replace('_', ' ', $item->category_id)) }}"
                                                    data-item-description="{{ $item->description ?? '' }}"
                                                    data-item-quantity="{{ $item->quantity ?? '' }}"
                                                    data-item-qr="{{ $item->qr_code ?? '' }}"
                                                    data-person-in-charge="{{ $personsInCharge[$item->room_id]->name ?? 'N/A' }}">
                                                    
                                                    <td>
                                                        <div class="item-info">
                                                            <strong class="d-block">{{ $item->item_name }}</strong>
                                                            <small class="text-muted d-none d-md-block">{{ Str::limit($item->description, 50) }}</small>
                                                            <small class="text-muted d-block d-sm-none">{{ Str::limit($item->description, 20) }}</small>
                                                        </div>
                                                    </td>
                                                    <td class="d-none d-sm-table-cell">
                                                        <span class="badge bg-secondary">{{ $item->room->name ?? 'N/A' }}</span>
                                                    </td>
                                                    <td class="d-none d-sm-table-cell">
                                                        <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $item->category_id)) }}</span>
                                                    </td>
                                                    <td class="d-none d-sm-table-cell">
                                                        <span class="badge bg-primary">{{ $item->quantity }}</span>
                                                    </td>
                                                    <td>
                                                        <select name="status[{{ $unit->id }}]" 
                                                                class="form-select form-select-sm status-select"
                                                                data-unit-id="{{ $unit->id }}">
                                                            @php
                                                                $statuses = [
                                                                    'Good condition' => 'Good condition',
                                                                    'New/Good condition' => 'New/Good condition',
                                                                    'Not working' => 'Not working',
                                                                    'Empty' => 'Empty',
                                                                    'New purchased' => 'New purchased',
                                                                    'Transfer to QA' => 'Transfer to QA',
                                                                    'Standard - not working' => 'Standard - not working',
                                                                    'Loss (Under investigation)' => 'Lost (Under investigation)',
                                                                    'Missing' => 'Missing'
                                                                ];
                                                            @endphp
                                                            @foreach ($statuses as $value => $label)
                                                                <option value="{{ $value }}" 
                                                                    {{ ($unit->status ?? '') == $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-primary view-details"
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#itemInfoModal">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="pagination-container mt-3">
                            <nav aria-label="Items pagination">
                                <ul class="pagination justify-content-center pagination-sm">
                                    {{-- Previous Page Link --}}
                                    @if ($items->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">Previous</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $items->previousPageUrl() }}">Previous</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($items->getUrlRange(1, $items->lastPage()) as $page => $url)
                                        @if ($page == $items->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($items->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $items->nextPageUrl() }}">Next</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                            <div class="text-center text-muted small">
                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} items
                            </div>
                        </div>
                    </form>
                @endif

                <div class="submit-section">
                    <div id="errorMessage" class="alert alert-danger" style="display: none;">
                        <span id="errorText"></span>
                    </div>
                    <button type="submit" form="scannerForm" class="btn btn-success btn-lg" id="submitBtn">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Legend Modal -->
        <div class="modal fade" id="statusLegendModal" tabindex="-1" aria-labelledby="statusLegendModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusLegendModalLabel">
                            <i class="fas fa-info-circle me-2"></i>Status Legend
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="status-legend">
                            <div class="status-item">
                                <span class="status-badge good">Good condition</span>
                                <p>Item is functional and in acceptable shape</p>
                            </div>
                            <div class="status-item">
                                <span class="status-badge new">New/Good condition</span>
                                <p>Newly acquired items in good working order</p>
                            </div>
                            <div class="status-item">
                                <span class="status-badge not-working">Not working</span>
                                <p>Item is currently non-functional</p>
                            </div>
                            <div class="status-item">
                                <span class="status-badge empty">Empty</span>
                                <p>Containers or units without intended contents</p>
                            </div>
                            <div class="status-item">
                                <span class="status-badge new-purchased">New purchased</span>
                                <p>Recently bought items</p>
                            </div>
                            <div class="status-item">
                                <span class="status-badge qa">Transfer to QA</span>
                                <p>Items transferred to Quality Assurance</p>
                            </div>
                            <div class="status-item">
                                <span class="status-badge standard">Standard - not working</span>
                                <p>Standard items that are non-functional</p>
                            </div>
                            <div class="status-item">
                                <span class="status-badge lost">Lost (Under investigation)</span>
                                <p>Items lost and under investigation</p>
                            </div>
                            <div class="status-item">
                                <span class="status-badge missing">Missing</span>
                                <p>Items that are missing</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item Details Modal -->
        <div class="modal fade" id="itemInfoModal" tabindex="-1" aria-labelledby="itemInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemInfoModalLabel">Item Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="detail-item">
                                    <label>Item Name:</label>
                                    <span id="modalItemName"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Room:</label>
                                    <span id="modalRoom"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Category:</label>
                                    <span id="modalCategory"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Quantity:</label>
                                    <span id="modalQuantity"></span>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="detail-item">
                                    <label>Status:</label>
                                    <span id="modalStatus"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Person in Charge:</label>
                                    <span id="modalPersonInCharge"></span>
                                </div>
                                <div class="detail-item">
                                    <label>QR Code:</label>
                                    <div id="modalQRCode">
                                        <div id="qrcode-container" class="d-flex justify-content-center">
                                            <img id="modalQRImage" src=""
                                                 alt="QR Code"
                                                 class="border rounded p-2"
                                                 style="width: 128px; height: 128px; display: none;">
                                            <div id="qrLoading" class="text-center">
                                                <i class="fas fa-spinner fa-spin"></i> Loading QR Code...
                                            </div>
                                        </div>
                                        <small class="text-muted d-flex justify-content-center">
                                            Code: <code class="small" id="modalQRText"></code>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="detail-item">
                            <label>Description:</label>
                            <p id="modalDescription"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/scannerblade.js') }}"></script>
    <script src="{{ asset('assets/js/scanner-fix.js') }}"></script>
</x-main-layout>

