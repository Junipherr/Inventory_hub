<x-main-layout>
    <div class="page-heading my-4">
        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head mb-3">
                    <div class="ibox-title"><h1 class="text-primary">Inventory Items</h1></div>
                </div>
                @if(session('success'))
                <div id="success-popup" class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1050; min-width: 250px;" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                @if($items->isEmpty())
                    <p class="text-muted">No items found.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Department</th>
                                    <th>Category</th>
                                    <th>Unit Number</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @foreach($item->units as $unit)
                                    <tr>
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->department }}</td>
                                        <td>{{ $item->category_id }}</td>
                                        <td>{{ $unit->unit_number }}</td>
                                        <td>
                                            @if($unit->last_checked_at)
                                                <span class="badge bg-success">Present</span>
                                            @else
                                                <span class="badge bg-danger">Missing</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-main-layout>
