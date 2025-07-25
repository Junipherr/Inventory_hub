<x-main-layout>
    <div class="page-heading">
        <h1 class="page-title">Add Items</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.html"><i class="la la-home font-20"></i></a>
            </li>
        </ol>
    </div>
    <div class="table-responsive">
        <div id="dynamicSuccessMessage" style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-success">
                <strong>Success!</strong> <span id="successMessageText"></span>
            </div>
        </div>
        <div class="page-content fade-in-up">
            <div class="row">
                <div class="col-md-6">
                    <div class="ibox">
                        <div class="ibox-head">
                            <div class="ibox-title">Add Items</div>
                            <div class="ibox-tools">
                                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            </div>
                        </div>
                        <div class="ibox-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Error!</strong> Please fix the following errors and try submitting again.
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form id="addItemForm">
                                @csrf
                                <div class="form-group">
                                    <label for="item_name">Item Name</label>
                                    <input type="text" class="form-control" name="item_name" id="item_name">
                                </div>
                                <div class="form-group">
                                    <label for="room_id">Room</label>
                                    <select name="room_id" class="form-control" id="roomSelect">
                                        <option value="">-- Select Room --</option>
@foreach($rooms as $room)
    <option value="{{ $room->id }}">{{ $room->name }}</option>
@endforeach
                            </select>
                        </div>
<div class="form-group" id="categoryGroup">
                            <label for="category_id">Category</label>
<select name="category_id" class="form-control" id="categorySelect">
    <option value="">-- Select Category --</option>
    <option value="computer_hardware_peripherals">Computer Hardware & Peripherals</option>
    <option value="office_classroom_furniture">Office and Classroom Furniture</option>
    <option value="appliances_electronics">Appliances and Electronics (Non-Computer)</option>
    <option value="classroom_office_supplies">Classroom/Office Supplies & Equipment (Miscellaneous)</option>
</select>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" class="form-control" rows="3" id="description"></textarea>
                        </div>
                        <button type="button" id="addItemButton" class="btn btn-primary">Add Item</button>
                    </form>
                </div>
            </div>
        </div>

<script>
    const rooms = @json($rooms);
</script>
                <div class="col-md-6">
                    <div class="ibox">
                        <div class="ibox-head">
                            <div class="ibox-title">Added Item</div>
                            <div class="ibox-tools">
                                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            </div>
                        </div>
                <div class="ibox-body" id="addedItemPanel" style="min-height: 300px; padding: 10px; transition: opacity 0.5s ease; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                            <p style="color: #6b7280; font-size: 1rem;">No item added yet.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
document.getElementById('addItemButton').addEventListener('click', function() {
    // Get form values
    const itemName = document.getElementById('item_name').value.trim();
    const roomId = document.getElementById('roomSelect').value;
    const categorySelect = document.getElementById('categorySelect');
    const category = categorySelect ? categorySelect.value : '';
    const description = document.getElementById('description').value.trim();

    if (!itemName || !roomId || !category) {
        alert('Please fill in all required fields: Item Name, Room, and Category.');
        return;
    }

    // Generate random alphanumeric code for qr_code (12 characters)
    function generateRandomCode(length) {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let result = '';
        for (let i = 0; i < length; i++) {
            result += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return result;
    }
    const randomCode = generateRandomCode(12);

    // Display added item details with QR code immediately
    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(randomCode)}&size=200x200`;
const addedItemPanel = document.getElementById('addedItemPanel');
const roomName = rooms.find(room => room.id == roomId)?.name || roomId;
const categoryNames = {
    computer_hardware_peripherals: "Computer Hardware & Peripherals",
    office_classroom_furniture: "Office and Classroom Furniture",
    appliances_electronics: "Appliances and Electronics (Non-Computer)",
    classroom_office_supplies: "Classroom/Office Supplies & Equipment (Miscellaneous)"
};
const categoryName = categoryNames[category] || category;
addedItemPanel.innerHTML = `
    <div style="width: 100%; max-width: 400px; text-align: center; font-family: Arial, sans-serif; color: #374151;">
        <h3 style="font-weight: 600; font-size: 1.25rem; margin-bottom: 12px; padding-bottom: 6px;">Added Item Details</h3>
        <p style="margin: 8px 0;"><strong>Item Name:</strong> ${itemName}</p>
        <p style="margin: 8px 0;"><strong>Room:</strong> ${roomName}</p>
        <p style="margin: 8px 0;"><strong>Category:</strong> ${categoryName}</p>
        <p style="margin: 8px 0 16px 0;"><strong>Description:</strong> ${description}</p>
        <img src="${qrCodeUrl}" alt="QR Code" style="border: 2px solid #3b82f6; border-radius: 8px; margin: 20px auto 0 auto; display: block; width: 200px; height: 200px;" />
        <form method="POST" action="{{ route('inventory.confirm') }}" id="confirmForm">
            @csrf
            <input type="hidden" name="item_name" value="${itemName}">
            <input type="hidden" name="room_id" value="${roomId}">
            <input type="hidden" name="category_id" value="${category}">
            <input type="hidden" name="description" value="${description}">
            <input type="hidden" name="qr_code" value="${randomCode}">
            <button type="submit" class="btn btn-success" id="confirmButton" style="padding: 10px 20px; font-size: 1rem; border-radius: 6px; transition: background-color 0.3s ease;">Confirm</button>
        </form>
    </div>
`;

    // Add event listener to confirm form for AJAX submission
    const confirmForm = document.getElementById('confirmForm');
    const confirmButton = document.getElementById('confirmButton');
    confirmForm.addEventListener('submit', function(event) {
        event.preventDefault();
        confirmButton.disabled = true;
        confirmButton.innerText = 'Submitting...';

        const formData = new FormData(confirmForm);

        fetch(confirmForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': formData.get('_token'),
                'Accept': 'application/json',
            },
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message dynamically
                const successMessage = document.getElementById('dynamicSuccessMessage');
                const successMessageText = document.getElementById('successMessageText');
                successMessageText.textContent = data.message || 'Item confirmed successfully.';
                successMessage.style.display = 'block';

                // Reset confirm button text and keep it disabled to prevent resubmission
                confirmButton.innerText = 'Confirmed';

                setTimeout(() => {
                    successMessage.style.transition = 'opacity 0.5s ease';
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                        successMessage.style.opacity = '1';
                    }, 500);
                }, 2000);
            } else {
                alert(data.message || 'Failed to confirm item.');
                confirmButton.disabled = false;
                confirmButton.innerText = 'Confirm';
            }
        })
        .catch(error => {
            confirmButton.disabled = false;
            confirmButton.innerText = 'Confirm';
            alert('Error submitting confirmation: ' + error.message);
        });
        // Do not re-enable the button on success to prevent resubmission
    });

    // Fade in animation for added item panel
    addedItemPanel.style.opacity = 0;
    setTimeout(() => {
        addedItemPanel.style.opacity = 1;
    }, 50);
});
</script>

</script>

</x-main-layout>
