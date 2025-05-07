<x-main-layout>
    <div class="page-heading">
        <div class="page-content fade-in-up">
        <div class="ibox">
        <div class="ibox-head">
            
            <div class="ibox-title"><h1>Inventory Items</h1></div>
        </div>
            @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}
            </div>@endif @if($items->isEmpty())
            <p>No items found.</p>@else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Department</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>@foreach($items as $item)<tr>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->department }}</td>
                    <td>{{ $item->category_id }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->description }}</td>
                </tr>@endforeach</tbody>
            </table>@endif
        </div>
        </div>
        </div>
        </x-main-layout>
