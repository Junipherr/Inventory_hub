<x-main-layout>
    <div class="page-heading my-4">
        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head mb-3">
                    <div class="ibox-title"><h1 class="text-primary">Inventory Items</h1></div>
                    <div class="ibox-tools">
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Item
                        </a>
                    </div>
                </div>
                
                @if(session('success'))
                <div id="success-popup" class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050; min-width: 250px;" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                
                @if($items->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No items found</h4>
                        <p class="text-muted">Add your first inventory item to get started.</p>
                        <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Add New Item
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Department</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->item_name }}</strong>
                                            <br>
                                            <small class="text-muted">ID: {{ $item->id }}</small>
                                        </td>
                                        <td>{{ $item->department ?? 'N/A' }}</td>
                                        <td>{{ $item->category_id ?? 'N/A' }}</td>
                                        <td>
                                            @if($item->units->count() > 0)
                                                <span class="badge bg-success">{{ $item->units->count() }} units</span>
                                            @else
                                                <span class="badge bg-secondary">No units</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('inventory.edit', $item->id) }}" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit Item">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('inventory.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirmDelete()">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-danger" 
                                                            title="Delete Item">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                
                                                <a href="{{ route('inventory.qrcode', $item->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Generate QR Code"
                                                   target="_blank">
                                                    <i class="fas fa-qrcode"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($items instanceof \Illuminate\Pagination\LengthAwarePaginator)
                        <div class="d-flex justify-content-center mt-4">
                            {{ $items->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- JavaScript for confirmation dialog -->
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this item? This action cannot be undone.');
        }

        // Auto-hide success popup after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const successPopup = document.getElementById('success-popup');
            if (successPopup) {
                setTimeout(function() {
                    successPopup.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</x-main-layout>
