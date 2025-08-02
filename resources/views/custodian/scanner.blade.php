<x-main-layout>

    <div class="page-heading ">
        <div id="dynamicSuccessMessage"
            style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-success">
                <strong>Success!</strong> <span id="successMessageText"></span>
            </div>
        </div>

        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title" style="display: flex; justify-content: space-between; align-items: center; gap: 5px;">
                        <h1 style="margin: 0;">QR Scanner - Items List</h1>

                        <div style="display: flex; align-items: center; ">
                            <button type="button"
                                class="btn btn-info btn-sm d-flex justify-content-center align-items-center"
                                id="showLegendBtn"
                                style="width: 30px; height: 30px; font-weight: 700; font-size: 1.2rem; padding: 0;">
                                ?
                            </button>
                            
                        </div>
                    </div>
                </div>

                @if ($items->isEmpty())
                    <p>No items found.</p>
                @else
<form method="POST" action="{{ route('scanner.update') }}" id="scannerForm">
    @csrf
    <div>
        <div class="table-responsive overflow-auto scrollbar-hidden" style="max-height: 400px;">
            <table class="custom-table" style=" margin-bottom: 0; border-collapse: separate; border-spacing: 0; width: 100%; table-layout: auto;">
                <thead class="sticky-top bg-white">
                    <tr>
                        <th>Item Name</th>
                        <th>Room</th>
                        <th>Category</th>
                        <th>Quantity</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($items as $item)
                    @foreach ($item->units as $unit)
                        <tr class="item-row" 
                            data-item-name="{{ $item->item_name }}" 
                            data-room="{{ $item->room->name ?? 'N/A' }}" 
                            data-room-id="{{ $item->room_id ?? 'N/A' }}"
                            data-category="{{ ucwords(str_replace('_', ' ', $item->category_id)) }}" 
                            data-quantity="{{ $item->quantity }}" 
                            data-description="{{ $item->description }}" 
                            data-status="{{ $unit->status ?? '' }}">
                            <td>{{ $item->item_name }}</td>
                            <td>{{ $item->room->name ?? 'N/A' }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $item->category_id)) }}</td>
                            <td>{{ $item->quantity }}</td>
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
    </div>
</form>
                @endif
            </div>
        </div>
        <div class="text-right">
                            <button type="submit" form="scannerForm" class="btn btn-sm btn-success "
                                id="submitCheckedUnitsButton"
                                style="padding: 6px 18px; font-weight: 600; font-size: 0.9rem;" >
                                Submit
                            </button>
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const submitButton = document.getElementById('submitCheckedUnitsButton');
                const successMessage = document.getElementById('dynamicSuccessMessage');
                const form = document.getElementById('scannerForm');
                const statusSelects = form ? form.querySelectorAll('select[name^="status"]') : [];

                // Re-enable the button when the success message is shown
                if (successMessage && submitButton) {
                    const observer = new MutationObserver(function (mutationsList) {
                        for (const mutation of mutationsList) {
                            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                                if (successMessage.style.display !== 'none') {
                                    submitButton.disabled = false;
                                }
                            }
                        }
                    });

                    observer.observe(successMessage, { attributes: true });
                }

                // Change button text to "Submitted" on form submit and disable button
                if (form && submitButton) {
                    form.addEventListener('submit', function () {
                        submitButton.disabled = true;
                        submitButton.textContent = 'Submitted';
                    });
                }

                // Change button text back to "Submit" when any status select is changed
                if (statusSelects.length > 0 && submitButton) {
                    statusSelects.forEach(function (select) {
                        select.addEventListener('change', function () {
                            submitButton.textContent = 'Submit';
                            submitButton.disabled = false;
                        });
                    });
                }
            });
        </script>
<style>
    #statusLegendModal .modal-dialog {
        max-width: 400px;
    }
    #statusLegendModal .modal-body {
        font-size: 0.85rem;
        padding: 1rem 1.5rem;
        max-height: 300px;
        overflow-y: auto;
    }
    #statusLegendModal .modal-body ul li {
        margin-bottom: 6px;
    }
</style>

<div class="modal fade" id="itemInfoModal" tabindex="-1" aria-labelledby="itemInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="itemInfoModalLabel">Item Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Room:</strong> <span id="modalRoom" class="badge bg-info text-dark"></span></p>
                <p><strong>Category:</strong> <span id="modalCategory"></span></p>
                <p><strong>Quantity:</strong> <span id="modalQuantity" class="badge bg-info text-dark"></span></p>
                <p><strong>Description:</strong> <span id="modalDescription"></span></p>
                <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                <p><strong>Person in Charge:</strong> <span id="modalPersonInCharge">N/A</span></p>
                <div id="modalUnitsSection" style="display:none;">
                    <h6 class="text-primary">Units</h6>
                    <table class="table table-sm table-bordered">
                        <thead class="table-secondary">
                            <tr>
                                <th>Unit Number</th>
                                <th>Last Checked At</th>
                            </tr>
                        </thead>
                        <tbody id="modalUnitsBody">
                        </tbody>
                    </table>
                </div>
                <div class="card mt-3 p-3 text-center" id="modalQrCodeSection" style="display:none;">
                    <canvas id="modalQrCodeCanvas"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Pass personsInCharge data to JavaScript
        const personsInCharge = @json($personsInCharge ?? []);
        const itemRows = document.querySelectorAll('tbody tr');
        const itemInfoModalElement = document.getElementById('itemInfoModal');
        const itemInfoModal = new bootstrap.Modal(itemInfoModalElement);
        
        // Keep track of the element that opened the modal
        let modalTriggerElement = null;

        // Handle modal hidden event to fix focus and aria-hidden issues
        itemInfoModalElement.addEventListener('hidden.bs.modal', function () {
            // Blur any element within the modal that might have retained focus
            const activeElement = document.activeElement;
            if (itemInfoModalElement.contains(activeElement)) {
                activeElement.blur();
            }
            
            // Return focus to the element that opened the modal
            if (modalTriggerElement) {
                // Small delay to ensure the modal is fully hidden before focusing
                setTimeout(() => {
                    modalTriggerElement.focus();
                    modalTriggerElement = null;
                }, 10);
            }
        });

        // Also handle the close button in the modal to ensure proper focus management
        const modalCloseButton = itemInfoModalElement.querySelector('.btn-close, .btn-secondary');
        if (modalCloseButton) {
            modalCloseButton.addEventListener('click', function() {
                if (modalTriggerElement) {
                    // Set focus to trigger element after a small delay
                    setTimeout(() => {
                        modalTriggerElement.focus();
                    }, 10);
                }
            });
        }

        itemRows.forEach(row => {
            row.style.cursor = 'pointer';
            row.addEventListener('click', (event) => {
                // Check if the click target is the status select or one of its children
                const statusSelect = row.querySelector('td:nth-child(6) select');
                if (statusSelect && (event.target === statusSelect || statusSelect.contains(event.target))) {
                    // Clicked on status select, don't show modal
                    return;
                }
                
                // Store the element that triggered the modal
                modalTriggerElement = event.currentTarget;
                
                const itemName = row.querySelector('td:nth-child(1)').textContent.trim();
                const room = row.querySelector('td:nth-child(2)').textContent.trim();
                const category = row.querySelector('td:nth-child(3)').textContent.trim();
                const quantity = row.querySelector('td:nth-child(4)').textContent.trim();
                const description = row.querySelector('td:nth-child(5)').textContent.trim();
                const status = row.querySelector('td:nth-child(6) select').value;
                
                // Get room ID from the row's data attributes
                const roomId = row.dataset.roomId || null;
                const personInCharge = roomId && personsInCharge[roomId] ? personsInCharge[roomId] : 'N/A';

                document.getElementById('itemInfoModalLabel').textContent = itemName + ' Information';
                document.getElementById('modalRoom').textContent = room;
                document.getElementById('modalCategory').textContent = category;
                document.getElementById('modalQuantity').textContent = quantity;
                document.getElementById('modalDescription').textContent = description;
                document.getElementById('modalStatus').textContent = status;
                document.getElementById('modalPersonInCharge').textContent = personInCharge;
                
                document.getElementById('modalUnitsSection').style.display = 'none';
                document.getElementById('modalQrCodeSection').style.display = 'none';

                itemInfoModal.show();
            });
        });
    });
</script>
</x-main-layout>
