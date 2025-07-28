<x-main-layout>

    <div class="page-heading">
        <div id="dynamicSuccessMessage"
            style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-success">
                <strong>Success!</strong> <span id="successMessageText"></span>
            </div>
        </div>

        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title" style="display: flex; justify-content: space-between; align-items: center;">
                        <h1 style="margin: 0;">QR Scanner - Items List</h1>

                        <div style="display: flex; align-items: center; gap: 10px;">
                            <button type="button"
                                class="btn btn-info btn-sm d-flex justify-content-center align-items-center"
                                id="showLegendBtn"
                                style="width: 30px; height: 30px; font-weight: 700; font-size: 1.2rem; padding: 0;">
                                ?
                            </button>
                            <button type="submit" form="scannerForm" class="btn btn-sm btn-success"
                                id="submitCheckedUnitsButton"
                                style="padding: 6px 18px; font-weight: 600; font-size: 0.9rem;">
                                Submit Checked Units
                            </button>
                        </div>
                    </div>
                </div>

                @if ($items->isEmpty())
                    <p>No items found.</p>
                @else
                    <form method="POST" action="{{ route('scanner.update') }}" id="scannerForm">
                        @csrf
                        <div style="max-height: 400px; overflow-y: auto;">
                            <table class="custom-table" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th>Check</th>
                                        <th>Item Name</th>
                                        <th>Room</th>
                                        <th>Category</th>
                                        <th>Unit Number</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        @foreach ($item->units as $unit)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="checked_units[]"
                                                        value="{{ $unit->id }}"
                                                        {{ $unit->last_checked_at ? 'checked' : '' }}>
                                                </td>
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->room->name ?? 'N/A' }}</td>
                                                <td>{{ ucwords(str_replace('_', ' ', $item->category_id)) }}</td>
                                                <td>{{ $unit->unit_number }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>
                                                    <select name="status[{{ $unit->id }}]" class="form-select">
                                                        <option value="Good condition"
                                                            {{ ($unit->status ?? '') == 'Good condition' ? 'selected' : '' }}>
                                                            Good condition</option>
                                                        <option value="New/Good condition"
                                                            {{ ($unit->status ?? '') == 'New/Good condition' ? 'selected' : '' }}>
                                                            New/Good condition</option>
                                                        <option value="Not working"
                                                            {{ ($unit->status ?? '') == 'Not working' ? 'selected' : '' }}>
                                                            Not working</option>
                                                        <option value="Empty"
                                                            {{ ($unit->status ?? '') == 'Empty' ? 'selected' : '' }}>
                                                            Empty</option>
                                                        <option value="New purchased"
                                                            {{ ($unit->status ?? '') == 'New purchased' ? 'selected' : '' }}>
                                                            New purchased</option>
                                                        <option value="Transfer to QA"
                                                            {{ ($unit->status ?? '') == 'Transfer to QA' ? 'selected' : '' }}>
                                                            Transfer to QA</option>
                                                        <option value="Standard - not working"
                                                            {{ ($unit->status ?? '') == 'Standard - not working' ? 'selected' : '' }}>
                                                            Standard - not working</option>
                                                        <option value="Loss (Under investigation)"
                                                            {{ ($unit->status ?? '') == 'Loss (Under investigation)' ? 'selected' : '' }}>
                                                            Lost (Under investigation)</option>
                                                        <option value="Missing"
                                                            {{ ($unit->status ?? '') == 'Missing' ? 'selected' : '' }}>
                                                            Missing</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusLegendModal" tabindex="-1" aria-labelledby="statusLegendModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusLegendModalLabel">Item Status Legend</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul>
                        <li><strong>Good condition:</strong> This is the most common status, indicating the item is
                            functional and in acceptable shape.</li>
                        <li><strong>New/Good condition:</strong> This status is used for newly acquired items that are
                            in good working order.</li>
                        <li><strong>Not working:</strong> This clearly indicates that the item is currently
                            non-functional.</li>
                        <li><strong>Empty:</strong> Used for certain containers or units, suggesting they are without
                            their intended contents.</li>
                        <li><strong>New purchased:</strong> This remark specifically highlights items that have been
                            recently bought, often with the unit price and total price.</li>
                        <li><strong>Transfer to QA:</strong> This remark indicates that an item (specifically a computer
                            table in one instance) has been transferred to Quality Assurance.</li>
                        <li><strong>Standard - not working:</strong> A specific note for an orbit fan, combining its
                            type with its non-functional status.</li>
                        <li><strong>Lost (Under investigation):</strong> Indicates items that are lost and under
                            investigation.</li>
                        <li><strong>Missing:</strong> Indicates items that are missing.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
