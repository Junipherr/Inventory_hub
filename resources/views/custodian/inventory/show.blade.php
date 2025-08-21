<x-main-layout>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-eye"></i> Item Details
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('inventory.items') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Items
                            </a>
                            <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit Item
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Basic Information</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Item Name:</strong></td>
                                        <td>{{ $item->item_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Category:</strong></td>
                                        <td>{{ ucwords(str_replace('_', ' ', $item->category_id)) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Room:</strong></td>
                                        <td>{{ $item->room->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Description:</strong></td>
                                        <td>{{ $item->description ?? 'No description provided' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Quantity:</strong></td>
                                        <td>{{ $item->quantity }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5>Additional Details</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>QR Code:</strong></td>
                                        <td>
                                            <code>{{ $item->qr_code }}</code>
                                            <br>
                                            <img src="{{ route('inventory.qrcode', $item->qr_code) }}" alt="QR Code" class="mt-2" style="width: 100px; height: 100px;">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Purchase Date:</strong></td>
                                        <td>{{ $item->purchase_date ? \Carbon\Carbon::parse($item->purchase_date)->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Purchase Price:</strong></td>
                                        <td>{{ $item->purchase_price ? '$' . number_format($item->purchase_price, 2) : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Warranty Expires:</strong></td>
                                        <td>{{ $item->warranty_expires ? \Carbon\Carbon::parse($item->warranty_expires)->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Condition:</strong></td>
                                        <td>{{ $item->condition ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>Item Units</h5>
                                @if($item->units->isEmpty())
                                    <p class="text-muted">No units registered for this item.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Unit #</th>
                                                    <th>Status</th>
                                                    <th>Last Checked</th>
                                                    <th>QR Code</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item->units as $unit)
                                                    <tr>
                                                        <td>{{ $unit->unit_number }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ $unit->status === 'available' ? 'success' : 'warning' }}">
                                                                {{ ucfirst($unit->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $unit->last_checked_at ? \Carbon\Carbon::parse($unit->last_checked_at)->format('M d, Y H:i') : 'Never' }}</td>
                                                        <td>
                                                            @if($unit->qr_code)
                                                                <code>{{ $unit->qr_code }}</code>
                                                                <br>
                                                                <img src="{{ route('inventory.qrcode', $unit->qr_code) }}" alt="Unit QR Code" style="width: 50px; height: 50px;">
                                                            @else
                                                                <span class="text-muted">No QR Code</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
