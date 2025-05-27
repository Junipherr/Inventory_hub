<x-main-layout>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Departments</div>
                    </div>
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line" role="tablist">
                            @foreach($departments as $index => $department)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link @if($index == 0) active @endif" id="tab-{{ $index }}-tab" data-toggle="tab" href="#tab-{{ $index }}" role="tab" aria-controls="tab-{{ $index }}" aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                        {{ $department }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach($departments as $index => $department)
                                <div class="tab-pane fade @if($index == 0) show active @endif" id="tab-{{ $index }}" role="tabpanel" aria-labelledby="tab-{{ $index }}-tab">
                                    @php
                                        $items = $itemsByDepartment[$department] ?? collect();
                                    @endphp
                                    @if($items->isEmpty())
                                        <p>No items found in {{ $department }}.</p>
                                    @else
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th>Department</th>
                                                    <th>Category</th>
                                                    <th>Quantity</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $item)
                                                    <tr>
                                                        <td>{{ $item->item_name }}</td>
                                                        <td>{{ $item->department }}</td>
                                                        <td>{{ $item->category_id }}</td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ $item->description }}</td>
                                                        <td>
                                                            <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-primary btn-sm" style="margin-right: 5px;" title="Edit">
                                                                <i class="ti-pencil"></i>
                                                            </a>
                                                            <form class="delete-item-form d-inline" data-item-id="{{ $item->id }}" onsubmit="return confirm('Are you sure you want to delete this item?');" action="{{ route('inventory.destroy', $item->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                                    <i class="ti-trash"></i>
                                                                </button>
                                                            </form>
                                                            <button
                                                                type="button"
                                                                class="btn btn-info btn-sm"
                                                                title="Item Info"
                                                                style="margin-left: 5px;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#itemInfoModal{{ $item->id }}"
                                                            >
                                                                <i class="ti-info-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @foreach($departments as $department)
            @php
                $items = $itemsByDepartment[$department] ?? collect();
            @endphp
            @foreach($items as $item)
                <!-- Bootstrap Modal -->
                <div class="modal fade" id="itemInfoModal{{ $item->id }}" tabindex="-1" aria-labelledby="itemInfoModalLabel{{ $item->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="itemInfoModalLabel{{ $item->id }}">{{ $item->item_name }} Info</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Department:</strong> {{ $item->department }}</p>
                                <p><strong>Category:</strong> {{ $item->category_id }}</p>
                                <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                                <p><strong>Description:</strong> {{ $item->description }}</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</x-main-layout>
