<x-main-layout>
    <div class="page-content fade-in-up">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox">
                    <div class="ibox-head">
                        <div class="ibox-title">Rooms</div>
                    </div>
                    <div class="ibox-body">
                        <ul class="nav nav-tabs tabs-line" role="tablist">
                            @foreach ($rooms as $index => $room)
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link @if ($index == 0) active @else bg-light text-dark @endif"
                                        id="tab-{{ $index }}-tab" data-toggle="tab"
                                        href="#tab-{{ $index }}" role="tab"
                                        aria-controls="tab-{{ $index }}"
                                        aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                        {{ $room->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach ($rooms as $index => $room)
                                <div class="tab-pane fade @if ($index == 0) show active @endif"
                                    id="tab-{{ $index }}" role="tabpanel"
                                    aria-labelledby="tab-{{ $index }}-tab">
                                    @php
                                        $items = $itemsByRoom[$room->id] ?? collect();
                                    @endphp
                                    @if ($items->isEmpty())
                                        <p>No items found in {{ $room->name }}.</p>
                                    @else
                                        <table class="table table-striped table-hover">
                                            <thead class="table-primary">
                                                <tr>
                                                    <th>Item Name</th>
                                                    <th>Room</th>
                                                    <th>Category</th>
                                                    <th>Quantity</th>
                                                    <th>Description</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $item)
                                                    <tr>
                                                        <td>{{ $item->item_name }}</td>
                                                        <td>{{ $room->name }}</td>
                                                        <td>{{ ucwords(str_replace('_', ' ', $item->category_id)) }}
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ $item->description }}</td>
                                                        <td>
                                                            <button type="button" class="btn btn-info btn-sm"
                                                                title="Item Info" style="margin-left: 5px;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#itemInfoModal{{ $item->id }}">
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

        @foreach ($rooms as $room)
            @php
                $items = $itemsByRoom[$room->id] ?? collect();
            @endphp
            @foreach ($items as $item)
                <!-- Bootstrap Modal -->
                <div class="modal fade" id="itemInfoModal{{ $item->id }}" tabindex="-1"
                    aria-labelledby="itemInfoModalLabel{{ $item->id }}" aria-hidden="true"
                    data-qr="{{ $item->qr_code }}">
                    <div class="modal-dialog modal-md modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="itemInfoModalLabel{{ $item->id }}">
                                    {{ $item->item_name }} Info</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Room:</strong> <span
                                        class="badge bg-info text-dark">{{ $room->name }}</span></p>
                                <p><strong>Person in Charge:</strong>
                                    @php
                                        $personInCharge = $personsInCharge[$room->id] ?? null;
                                    @endphp
                                    @if ($personInCharge)
                                        {{ $personInCharge->name }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Category:</strong> {{ ucwords(str_replace('_', ' ', $item->category_id)) }}
                                </p>
                                <p><strong>Quantity:</strong> <span
                                        class="badge bg-info text-dark">{{ $item->quantity }}</span></p>
                                <p><strong>Description:</strong> {{ $item->description }}</p>
                                @if ($item->units->isNotEmpty())
                                    <div class="mt-3">
                                        <h6 class="text-primary">Units</h6>
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th>Unit Number</th>
                                                    <th>Last Checked At</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item->units as $unit)
                                                    <tr>
                                                        <td>{{ $unit->unit_number }}</td>
                                                        <td>{{ $unit->last_checked_at ? $unit->last_checked_at->timezone(config('app.timezone'))->format('Y-m-d H:i') : 'Never' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="card mt-3 p-3 text-center">
                                    <canvas id="qrcode-{{ $item->id }}"></canvas>
                                </div>
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
