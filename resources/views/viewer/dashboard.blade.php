@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp
<x-main-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ asset('assets/css/scannerblade.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/scannerblade-responsive.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('assets/css/viewer-dashboard.css') }}" rel="stylesheet"> --}}
    
    <div class="scanner-container">
        <!-- Success Notification -->
        <div id="dynamicSuccessMessage"
            style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-success">
                <strong>Success!</strong> <span id="successMessageText"></span>
            </div>
        </div>

        <!-- Error Notification -->
        <div id="errorMessage" class="alert alert-danger" style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <span id="errorText"></span>
        </div>

        <!-- Viewer Dashboard Header -->
        <div class="scanner-header">
            <div class="header-content">
                <h1 class="scanner-title">
                    <i class="fas fa-eye"></i> My Inventory View
                </h1>
                <div class="header-stats">
                    <span class="stat-item">
                        <i class="fas fa-list"></i> 
                        <span id="totalItems">{{ $items->count() }}</span> Items
                    </span>
                    <span class="stat-item">
                        <i class="fas fa-building"></i>
                        Room: <strong>{{ $room->name ?? 'No Room Assigned' }}</strong>
                    </span>
                </div>
            </div>
        </div>

        <div class="scanner-content">
            <!-- QR Scanner Section -->
            <div class="scanner-section mb-4">
                <div class="scanner-card">
                    <div class="scanner-header-card">
                        <h3><i class="fas fa-camera"></i> QR Code Scanner</h3>
                        <div class="scanner-controls">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="startViewerScanner">
                                <i class="fas fa-play"></i> Start Scanner
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="stopViewerScanner" style="display: none;">
                                <i class="fas fa-stop"></i> Stop Scanner
                            </button>
                        </div>
                    </div>
                    
                    <div class="scanner-viewport">
                        <video id="viewerScannerVideo" autoplay muted playsinline style="display: none;"></video>
                        <canvas id="viewerScannerCanvas" style="display: none;"></canvas>
                        <div id="viewerScannerPlaceholder" class="scanner-placeholder">
                            <i class="fas fa-qrcode fa-3x"></i>
                            <p>Click "Start Scanner" to scan QR codes for your items</p>
                        </div>
                    </div>

                    <div class="scanner-results">
                        <div id="viewerScanResult" class="scan-result" style="display: none;">
                            <h5>Scanned Item:</h5>
                            <p id="viewerScannedCode"></p>
                            <button type="button" class="btn btn-sm btn-success" id="findViewerItem">
                                <i class="fas fa-search"></i> Find in My Items
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Manual QR Input for Viewer -->
                <div class="manual-input-card mt-3">
                    <h5><i class="fas fa-keyboard"></i> Manual QR Lookup</h5>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" id="viewerManualQrInput" 
                               placeholder="Enter QR code to find item">
                        <button class="btn btn-primary btn-sm" type="button" id="viewerManualScanBtn">
                            <i class="fas fa-search"></i> Find
                        </button>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="items-section">
                <div class="items-header">
                    <h3><i class="fas fa-list"></i> My Assigned Items</h3>
                    <div class="items-controls">
                        <div class="search-box">
                            <input type="text" class="form-control form-control-sm" id="searchItems" 
                                   placeholder="Search items...">
                            <i class="fas fa-search"></i>
                        </div>
                        <button type="button" class="btn btn-outline-info btn-sm" id="showLegendBtn">
                            <i class="fas fa-info-circle"></i> Legend
                        </button>
                    </div>
                </div>

                @if ($items->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x"></i>
                        <h4>No items found</h4>
                        <p>No items are currently assigned to your room.</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('viewer.update-status') }}" id="viewerForm">
                        @csrf
                        <div class="row" id="itemsGrid">
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
                                                {{ $room->name ?? 'N/A' }}
                                            </p>
                                            <p class="text-muted small mb-3">
                                                {{ Str::limit($item->description, 50) }}
                                            </p>

                                            <div class="mb-3">
                                                <label class="form-label small">Status</label>
                                                @foreach ($item->units as $unit)
                                                    <select name="status[{{ $unit->id }}]"
                                                            class="form-select form-select-sm status-select mb-1"
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
                                                @endforeach
                                            </div>

                                            <div class="d-flex gap-1">
                                                <button type="button"
                                                        class="btn btn-primary btn-sm flex-fill view-details"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#itemInfoModal"
                                                        data-item-id="{{ $item->id }}"
                                                        data-item-name="{{ $item->item_name }}"
                                                        data-room="{{ $item->room->name ?? 'N/A' }}"
                                                        data-category="{{ ucwords(str_replace('_', ' ', $item->category_id)) }}"
                                                        data-quantity="{{ $item->quantity }}"
                                                        data-description="{{ $item->description ?? '' }}"
                                                        data-qr="{{ $item->qr_code ?? '' }}">
                                                    <i class="fas fa-eye mr-1"></i>
                                                    Details
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="submit-section mt-4">
                            <button type="submit" form="viewerForm" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- Status Legend Modal -->
        <div class="modal fade" id="statusLegendModal" tabindex="-1" aria-labelledby="statusLegendModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusLegendModalLabel">
                            <i class="fas fa-info-circle"></i> Status Legend
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
    <script src="{{ asset('assets/js/viewer-dashboard.js') }}"></script>
</x-main-layout>