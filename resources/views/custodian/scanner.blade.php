<x-main-layout>
    <div class="page-heading">
        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title"><h1>QR Scanner - Items List</h1></div>
                </div>
                @if($items->isEmpty())
                    <p>No items found.</p>
                @else
                    <form method="POST" action="{{ route('scanner.update') }}">
                        @csrf
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Check</th>
                                    <th>Item Name</th>
                                    <th>Department</th>
                                    <th>Category</th>
                                    <th>Unit Number</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    @foreach($item->units as $unit)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="checked_units[]" value="{{ $unit->id }}" {{ $unit->last_checked_at ? 'checked' : '' }}>
                                        </td>
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->department }}</td>
                                        <td>{{ $item->category_id }}</td>
                                        <td>{{ $unit->unit_number }}</td>
                                        <td>{{ $item->description }}</td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Submit Checked Units</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-main-layout>
