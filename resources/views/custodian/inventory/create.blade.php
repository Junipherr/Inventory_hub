<x-main-layout>
    @push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    @endpush

    <div class="page-heading">
        <h1 class="page-title">Add New Inventory Items</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}"><i class="la la-home font-20"></i></a>
            </li>
            <li class="breadcrumb-item active">Add Items</li>
        </ol>
    </div>

    <!-- Dynamic Notification Container -->
    <div id="dynamicSuccessMessage" 
         style="position: fixed; top: 20px; right: 20px; z-index: 1050; width: auto; max-width: 400px; display: none;">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle"></i> 
            <span id="successMessageText"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    <div id="dynamicErrorMessage" 
         style="position: fixed; top: 20px; right: 20px; z-index: 1050; width: auto; max-width: 400px; display: none;">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle"></i> 
            <span id="errorMessageText"></span>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle"></i> Please fix the following errors:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="page-content fade-in-up">
        <!-- Progress Indicator -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: 0%" 
                         id="progressBar">
                        Step 1 of 3
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Form -->
            <div class="col-lg-8">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">
                            <i class="fa fa-plus-circle"></i> Item Details
                        </div>
                        <div class="ibox-tools">
                            <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                        </div>
                    </div>
                    <div class="ibox-body">
                        <form id="inventoryForm" method="POST" action="/inventory">
                            @csrf
                            
                            <!-- Step 1: Basic Information -->
                            <div class="form-step" id="step1">
                                <h4 class="text-info mb-3">Step 1: Basic Information</h4>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="item_name" class="required">Item Name *</label>
                                            <input type="text" 
                                                   class="form-control @error('item_name') is-invalid @enderror" 
                                                   name="item_name" 
                                                   id="item_name"
                                                   placeholder="e.g., Dell Laptop, Office Chair"
                                                   value="{{ old('item_name') }}"
                                                   required>
                                            <small class="form-text text-muted">Enter a descriptive name for the item</small>
                                            @error('item_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category_id" class="required">Category *</label>
                                            <select name="category_id" 
                                                    class="form-control select2 @error('category_id') is-invalid @enderror" 
                                                    id="categorySelect" 
                                                    required>
                                                <option value="">-- Select Category --</option>
                                                <option value="computer_hardware_peripherals" {{ old('category_id') == 'computer_hardware_peripherals' ? 'selected' : '' }}>💻 Computer Hardware & Peripherals</option>
                                                <option value="office_classroom_furniture" {{ old('category_id') == 'office_classroom_furniture' ? 'selected' : '' }}>🪑 Office and Classroom Furniture</option>
                                                <option value="appliances_electronics" {{ old('category_id') == 'appliances_electronics' ? 'selected' : '' }}>📺 Appliances and Electronics</option>
                                                <option value="classroom_office_supplies" {{ old('category_id') == 'classroom_office_supplies' ? 'selected' : '' }}>📚 Classroom/Office Supplies</option>
                                                <option value="networking_equipment" {{ old('category_id') == 'networking_equipment' ? 'selected' : '' }}>🌐 Networking Equipment</option>
                                                <option value="security_systems" {{ old('category_id') == 'security_systems' ? 'selected' : '' }}>🔒 Security Systems</option>
                                                <option value="laboratory_equipment" {{ old('category_id') == 'laboratory_equipment' ? 'selected' : '' }}>🧪 Laboratory Equipment</option>
                                                <option value="medical_equipment" {{ old('category_id') == 'medical_equipment' ? 'selected' : '' }}>🏥 Medical Equipment</option>
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="room_id" class="required">Room Location *</label>
                                            <select name="room_id" 
                                                    class="form-control select2 @error('room_id') is-invalid @enderror" 
                                                    id="roomSelect" 
                                                    required>
                                                <option value="">-- Select Room --</option>
                                                @foreach($rooms as $room)
                                                    <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('room_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="quantity" class="required">Quantity *</label>
                                            <div class="input-group">
                                                <input type="number" 
                                                       class="form-control @error('quantity') is-invalid @enderror" 
                                                       name="quantity" 
                                                       id="quantity" 
                                                       min="1" 
                                                       value="{{ old('quantity', 1) }}"
                                                       required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">units</span>
                                                </div>
                                                @error('quantity')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Additional Details -->
                            <div class="form-step d-none" id="step2">
                                <h4 class="text-info mb-3">Step 2: Additional Details</h4>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" 
                                                      class="form-control @error('description') is-invalid @enderror" 
                                                      rows="4" 
                                                      id="description"
                                                      placeholder="Provide detailed description, specifications, or notes...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="purchase_date">Purchase Date</label>
                                            <input type="date" 
                                                   class="form-control @error('purchase_date') is-invalid @enderror" 
                                                   name="purchase_date" 
                                                   id="purchase_date"
                                                   value="{{ old('purchase_date') }}" required>
                                            @error('purchase_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="purchase_price">Purchase Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">₱</span>
                                                </div>
                                                <input type="number" 
                                                       class="form-control @error('purchase_price') is-invalid @enderror" 
                                                       name="purchase_price" 
                                                       id="purchase_price"
                                                       step="0.01"
                                                       min="0"
                                                       value="{{ old('purchase_price') }}">
                                                @error('purchase_price')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="warranty_expires">Warranty Expires</label>
                                            <input type="date" 
                                                   class="form-control @error('warranty_expires') is-invalid @enderror" 
                                                   name="warranty_expires" 
                                                   id="warranty_expires"
                                                   value="{{ old('warranty_expires') }}">
                                            @error('warranty_expires')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="condition">Condition</label>
                                            <select name="condition" class="form-control @error('condition') is-invalid @enderror" id="condition">
                                                <option value="new" {{ old('condition') == 'new' ? 'selected' : '' }}>🆕 New</option>
                                                <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>✅ Good</option>
                                                <option value="fair" {{ old('condition') == 'fair' ? 'selected' : '' }}>⚠️ Fair</option>
                                                <option value="poor" {{ old('condition') == 'poor' ? 'selected' : '' }}>❌ Poor</option>
                                            </select>
                                            @error('condition')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3: QR Code & Confirmation -->
                            <div class="form-step d-none" id="step3">
                                <h4 class="text-info mb-3">Step 3: QR Code & Confirmation</h4>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Item QR Code</label>
                                            <div class="text-center p-3 border rounded">
                                                <div id="qrPreview" class="d-inline-block" style="min-height: 200px; min-width: 200px;">
                                                    <!-- QR code will be generated here -->
                                                </div>
                                                <p class="mt-2 text-muted">
                                                    <small>Scan this code to view item details</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h5 class="mb-0">Item Summary</h5>
                                            </div>
                                            <div class="card-body">
                                                <dl class="row">
                                                    <dt class="col-sm-5">Item Name:</dt>
                                                    <dd class="col-sm-7" id="summaryItemName">-</dd>
                                                    
                                                    <dt class="col-sm-5">Category:</dt>
                                                    <dd class="col-sm-7" id="summaryCategory">-</dd>
                                                    
                                                    <dt class="col-sm-5">Room:</dt>
                                                    <dd class="col-sm-7" id="summaryRoom">-</dd>
                                                    
                                                    <dt class="col-sm-5">Quantity:</dt>
                                                    <dd class="col-sm-7" id="summaryQuantity">-</dd>
                                                </dl>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="qr_code" id="qrCodeInput">
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="form-group text-right mt-4">
                                <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                                    <i class="fa fa-arrow-left"></i> Previous
                                </button>
                                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">
                                    Next <i class="fa fa-arrow-right"></i>
                                </button>
                                <button type="submit" class="btn btn-success d-none" id="submitBtn">
                                    <i class="fa fa-check"></i> Confirm & Add Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Preview Panel -->
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">
                            <i class="fa fa-eye"></i> Live Preview
                        </div>
                    </div>
                    <div class="ibox-body">
                        <div id="livePreview">
                            <div class="text-center text-muted py-5">
                                <i class="fa fa-info-circle fa-3x mb-3"></i>
                                <p>Fill in the form to see live preview</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Quick Actions</div>
                    </div>
                    <div class="ibox-body">
                        <div class="list-group">
                            <a href="/inventory/items" class="list-group-item list-group-item-action">
                                <i class="fa fa-list"></i> View All Items
                            </a>
                            <a href="/scanner" class="list-group-item list-group-item-action">
                                <i class="fa fa-qrcode"></i> QR Code Scanner
                            </a>
                            <a href="/dashboard" class="list-group-item list-group-item-action">
                                <i class="fa fa-tachometer-alt"></i> Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Success!</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <i class="fa fa-check-circle fa-5x text-success mb-3"></i>
                    <h4>Item Added Successfully!</h4>
                    <p>Your inventory item has been added to the system.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal">
                        <i class="fa fa-check"></i> OK
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
