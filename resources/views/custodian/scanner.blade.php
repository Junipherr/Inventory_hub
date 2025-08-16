<x-main-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="scanner-container">
        <!-- Success Notification -->
        <div id="dynamicSuccessMessage"
            style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-success">
                <strong>Success!</strong> <span id="successMessageText"></span>
            </div>
        </div>

        <!-- Scanner Header -->
        <div class="scanner-header">
            <div class="header-content">
                <h1 class="scanner-title">
                    <i class="fas fa-qrcode"></i> QR Scanner & Inventory Manager
                </h1>
                <div class="header-stats">
                    <span class="stat-item">
                        <i class="fas fa-list"></i> 
                        <span id="totalItems">{{ $items->count() }}</span> Items
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
                <div class="scanner-card">
                    <div class="scanner-header-card">
                        <h3><i class="fas fa-camera"></i> QR Scanner</h3>
                        <div class="scanner-controls">
                            <button type="button" class="btn btn-outline-primary" id="startScanner">
                                <i class="fas fa-play"></i> Start Camera
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="stopScanner" style="display: none;">
                                <i class="fas fa-stop"></i> Stop Camera
                            </button>
                        </div>
                    </div>
                    
                    <div class="scanner-viewport">
                        <video id="scannerVideo" autoplay muted playsinline style="display: none;"></video>
                        <canvas id="scannerCanvas" style="display: none;"></canvas>
                        <div id="scannerPlaceholder" class="scanner-placeholder">
                            <i class="fas fa-camera fa-3x"></i>
                            <p>Click "Start Camera" to begin scanning QR codes</p>
                        </div>
                    </div>

                    <div class="scanner-results">
                        <div id="scanResult" class="scan-result" style="display: none;">
                            <h5>Last Scanned:</h5>
                            <p id="scannedCode"></p>
                            <button type="button" class="btn btn-sm btn-success" id="findItem">
                                <i class="fas fa-search"></i> Find Item
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Manual QR Input -->
                <div class="manual-input-card">
                    <h5><i class="fas fa-keyboard"></i> Manual QR Input</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" id="manualQrInput" 
                               placeholder="Enter QR code manually">
                        <button class="btn btn-primary" type="button" id="manualScanBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Items List Section -->
            <div class="items-section">
                <div class="items-header">
                    <h3><i class="fas fa-list"></i> Inventory Items</h3>
                    <div class="items-controls">
                        <div class="search-box">
                            <input type="text" class="form-control" id="searchItems" 
                                   placeholder="Search items...">
                            <i class="fas fa-search"></i>
                        </div>
                        <button type="button" class="btn btn-outline-info" id="showLegendBtn">
                            <i class="fas fa-info-circle"></i> Legend
                        </button>
                    </div>
                </div>

                @if ($items->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x"></i>
                        <h4>No items found</h4>
                        <p>Add some items to start scanning</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('scanner.update') }}" id="scannerForm">
                        @csrf
                        <div class="items-table-container">
                            <div class="table-responsive">
                                <table class="table table-hover" id="itemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Room</th>
                                            <th>Category</th>
                                            <th>Qty</th>
                                            <th>Status</th>
                                            <th>Actions</th>
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
                                                            <strong>{{ $item->item_name }}</strong>
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
                                                    <td>
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
                    </form>
                @endif

                <div class="submit-section">
                    <div id="errorMessage" class="alert alert-danger" style="display: none;">
                        <span id="errorText"></span>
                    </div>
                    <button type="submit" form="scannerForm" class="btn btn-success btn-lg" id="submitBtn">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>
        </div>

        <!-- Status Legend Modal -->
        <div class="modal fade" id="statusLegendModal" tabindex="-1" aria-labelledby="statusLegendModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
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
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemInfoModalLabel">Item Details</h5>
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
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <label>Status:</label>
                                    <span id="modalStatus"></span>
                                </div>
                                <div class="detail-item">
                                    <label>Person in Charge:</label>
                                    <span id="modalPersonInCharge">
                                        
                                    </span>
                                </div>
                                <div class="detail-item">
                                    <label>QR Code:</label>
                                    <div id="modalQRCode">
<div id="qrcode-container-{{ $item->id }}" class="d-flex justify-content-center">
 <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(128)->margin(2)->generate($item->qr_code ?? 'N/A')) }}" 
                                                     alt="QR Code for {{ $item->item_name }}" 
                                                     class="border rounded p-2"
                                                     style="width: 128px; height: 128px;">
                                            </div>
                                             <small class="text-muted d-flex justify-content-center">
                                                Code: <code class="small">{{ $item->qr_code ?? 'N/A' }}</code>
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

    <style>
        .scanner-container {
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .scanner-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .scanner-title {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 300;
        }

        .header-stats {
            display: flex;
            gap: 30px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.1rem;
        }

        .scanner-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }

        .scanner-section {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .scanner-card, .manual-input-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
        }

        .scanner-header-card {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .scanner-viewport {
            position: relative;
            width: 100%;
            height: 300px;
            background: #f8f9fa;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .scanner-placeholder {
            text-align: center;
            color: #6c757d;
        }

        #scannerVideo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .scan-result {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        .items-section {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
        }

        .items-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .items-controls {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-right: 40px;
            width: 300px;
        }

        .search-box i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .items-table-container {
            margin-bottom: 25px;
        }

        .item-info {
            display: flex;
            flex-direction: column;
        }

        .item-info small {
            font-size: 0.85rem;
            margin-top: 2px;
        }

        .submit-section {
            text-align: right;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .status-legend {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .status-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .status-badge.good { background: #d4edda; color: #155724; }
        .status-badge.new { background: #cce5ff; color: #004085; }
        .status-badge.not-working { background: #f8d7da; color: #721c24; }
        .status-badge.empty { background: #fff3cd; color: #856404; }
        .status-badge.new-purchased { background: #d1ecf1; color: #0c5460; }
        .status-badge.qa { background: #e2e3e5; color: #383d41; }
        .status-badge.standard { background: #f8d7da; color: #721c24; }
        .status-badge.lost { background: #f5c6cb; color: #721c24; }
        .status-badge.missing { background: #f8d7da; color: #721c24; }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-item label {
            font-weight: 600;
            color: #495057;
            margin-right: 10px;
        }

        @media (max-width: 768px) {
            .scanner-content {
                grid-template-columns: 1fr;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .scanner-title {
                font-size: 2rem;
            }

            .items-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box input {
                width: 100%;
            }
        }

        .highlighted {
            background-color: #fff3cd !important;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { background-color: #fff3cd; }
            50% { background-color: #ffeaa7; }
            100% { background-color: #fff3cd; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Scanner functionality
            let scanner = null;
            let videoStream = null;

            const startScannerBtn = document.getElementById('startScanner');
            const stopScannerBtn = document.getElementById('stopScanner');
            const video = document.getElementById('scannerVideo');
            const canvas = document.getElementById('scannerCanvas');
            const placeholder = document.getElementById('scannerPlaceholder');
            const scanResult = document.getElementById('scanResult');
            const scannedCode = document.getElementById('scannedCode');
            const findItemBtn = document.getElementById('findItem');
            const manualQrInput = document.getElementById('manualQrInput');
            const manualScanBtn = document.getElementById('manualScanBtn');

            // Search functionality
            const searchInput = document.getElementById('searchItems');
            const itemsTable = document.getElementById('itemsTable');

            // Start camera scanner
            startScannerBtn.addEventListener('click', async () => {
                try {
                    videoStream = await navigator.mediaDevices.getUserMedia({ 
                        video: { facingMode: 'environment' } 
                    });
                    video.srcObject = videoStream;
                    video.style.display = 'block';
                    placeholder.style.display = 'none';
                    startScannerBtn.style.display = 'none';
                    stopScannerBtn.style.display = 'inline-block';
                    
                    // Initialize QR scanner
                    startQrScanning();
                } catch (error) {
                    console.error('Error accessing camera:', error);
                    alert('Unable to access camera. Please ensure camera permissions are granted.');
                }
            });

            // Stop camera scanner
            stopScannerBtn.addEventListener('click', () => {
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                    videoStream = null;
                }
                video.style.display = 'none';
                placeholder.style.display = 'flex';
                startScannerBtn.style.display = 'inline-block';
                stopScannerBtn.style.display = 'none';
            });

            // Manual QR input
            manualScanBtn.addEventListener('click', () => {
                const qrCode = manualQrInput.value.trim();
                if (qrCode) {
                    handleQrCode(qrCode);
                }
            });

            manualQrInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    const qrCode = manualQrInput.value.trim();
                    if (qrCode) {
                        handleQrCode(qrCode);
                    }
                }
            });

            // Search functionality
            searchInput.addEventListener('input', (e) => {
                const searchTerm = e.target.value.toLowerCase();
                const rows = itemsTable.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(searchTerm) ? '' : 'none';
                });
            });

            // QR code handling
            function handleQrCode(qrCode) {
                scannedCode.textContent = qrCode;
                scanResult.style.display = 'block';
                
                // Find and highlight the item
                const rows = itemsTable.querySelectorAll('tbody tr');
                let found = false;
                
                rows.forEach(row => {
                    const rowQrCode = row.dataset.qrCode;
                    if (rowQrCode === qrCode) {
                        row.classList.add('highlighted');
                        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        found = true;
                        
                        // Remove highlight after 3 seconds
                        setTimeout(() => {
                            row.classList.remove('highlighted');
                        }, 3000);
                    }
                });

                if (!found) {
                    alert('Item not found with this QR code.');
                }
            }

            // Find item button
            findItemBtn.addEventListener('click', () => {
                const qrCode = scannedCode.textContent;
                if (qrCode) {
                    handleQrCode(qrCode);
                }
            });

            // Status legend modal
            document.getElementById('showLegendBtn').addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('statusLegendModal'));
                modal.show();
            });

            // Item details modal
            document.querySelectorAll('.view-details').forEach(button => {
                button.addEventListener('click', (e) => {
                    const row = e.target.closest('tr');
                    const itemName = row.dataset.itemName;
                    const room = row.dataset.room;
                    const category = row.dataset.category;
                    const quantity = row.dataset.itemQuantity;
                    const status = row.querySelector('.status-select').value;
                    const personInCharge = row.dataset.personInCharge;
                    const qrCode = row.dataset.itemQr;
                    const description = row.dataset.itemDescription;

                    document.getElementById('modalItemName').textContent = itemName;
                    document.getElementById('modalRoom').textContent = room;
                    document.getElementById('modalCategory').textContent = category;
                    document.getElementById('modalQuantity').textContent = quantity;
                    document.getElementById('modalStatus').textContent = status;
                    document.getElementById('modalPersonInCharge').textContent = personInCharge;
                    document.getElementById('modalDescription').textContent = description || 'No description available';
                    
                    // Display QR code
                    const qrCodeImage = document.getElementById('qrCodeImage');
                    if (qrCode) {
                        // Generate QR code image using Google Chart API
                        const qrUrl = `https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=${encodeURIComponent(qrCode)}`;
                        qrCodeImage.src = qrUrl;
                        qrCodeImage.style.display = 'block';
                    } else {
                        qrCodeImage.style.display = 'none';
                        document.getElementById('modalQRCode').innerHTML = '<span>No QR code available</span>';
                    }
                });
            });

            // Form submission
            document.getElementById('scannerForm').addEventListener('submit', (e) => {
                e.preventDefault();
                
                const submitBtn = document.getElementById('submitBtn');
                const errorDiv = document.getElementById('errorMessage');
                const errorText = document.getElementById('errorText');
                
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
                
                // Hide any previous error messages
                errorDiv.style.display = 'none';

                fetch(e.target.action, {
                    method: 'POST',
                    body: new FormData(e.target),
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage(data.message || 'Changes saved successfully!');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                    } else {
                        showErrorMessage(data.message || 'Error saving changes. Please try again.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('Error saving changes. Please check your connection and try again.');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
                });
            });

            // Success message
            let successTimeout; // Add this variable at the top of your <script> block

            function showSuccessMessage(message) {
                const successDiv = document.getElementById('dynamicSuccessMessage');
                const messageText = document.getElementById('successMessageText');
                messageText.textContent = message;
                successDiv.style.display = 'block';

                // Clear any previous timeout to prevent stacking
                if (successTimeout) {
                    clearTimeout(successTimeout);
                }
                successTimeout = setTimeout(() => {
                    successDiv.style.display = 'none';
                }, 3000);
            }

            // Error message
            function showErrorMessage(message) {
                const errorDiv = document.getElementById('errorMessage');
                const errorText = document.getElementById('errorText');
                errorText.textContent = message;
                errorDiv.style.display = 'block';
                
                setTimeout(() => {
                    errorDiv.style.display = 'none';
                }, 5000);
            }

            // QR scanning simulation (for demo purposes)
            function startQrScanning() {
                // This is a placeholder for actual QR scanning
                // In a real implementation, you would use a library like jsQR
                console.log('QR scanning started');
            }

            // Update scanned items count
            function updateScannedCount() {
                const checkedItems = document.querySelectorAll('.status-select').length;
                document.getElementById('scannedItems').textContent = checkedItems;
            }

            // Initialize
            updateScannedCount();
        });
    </script>
    <script src="{{ asset('assets/js/scanner-fix.js') }}"></script>

</x-main-layout>
