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
        @if (session('success'))
            <div>
                <div class="alert alert-success position-fixed" id="successNotification" style="top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px;">
                    <strong>Success!</strong> {{ session('success') }}
                </div>
            </div>
        @endif
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
                                    <label for="department">Department</label>
                                    <select name="department" class="form-control" id="departmentSelect">
                                        <option value="">-- Select Department --</option>
                                        <option value="nursing_department">Nursing Department</option>
                                        <option value="computer_department">Computer Department</option>
                                    </select>
                                </div>
                                <div class="form-group" id="categoryGroup" style="display:none;">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" class="form-control" id="categorySelect">
                                        <option value="">-- Select Category --</option>
                                        <optgroup label="Nursing Department" id="nursing_department_categories">
                                            <option value="medical_equipment">Medical Equipment</option>
                                            <option value="lab_materials">Lab Materials</option>
                                            <option value="protective_gear">Protective Gear</option>
                                            <option value="first_aid_supplies">First Aid Supplies</option>
                                        </optgroup>
                                        <optgroup label="Computer Department" id="computer_department_categories">
                                            <option value="computer_hardware">Computer Hardware</option>
                                            <option value="networking_equipment">Networking Equipment</option>
                                            <option value="software_licenses">Software Licenses</option>
                                            <option value="accessories_peripherals">Accessories & Peripherals</option>
                                        </optgroup>
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

<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script>
document.getElementById('addItemButton').addEventListener('click', function() {
    // Get form values
    const itemName = document.getElementById('item_name').value.trim();
    const department = document.getElementById('departmentSelect').value;
    const categorySelect = document.getElementById('categorySelect');
    const category = categorySelect ? categorySelect.value : '';
    const description = document.getElementById('description').value.trim();

    if (!itemName || !department || !category) {
        alert('Please fill in all required fields: Item Name, Department, and Category.');
        return;
    }

    // Generate QR code data as JSON string
    const qrData = JSON.stringify({
        item_name: itemName,
        department: department,
        category_id: category,
        description: description
    });

    // Generate QR code as data URL
    QRCode.toDataURL(qrData, { width: 200, margin: 2 }, function (err, url) {
        if (err) {
            console.error(err);
            alert('Failed to generate QR code.');
            return;
        }

        // Display added item details and QR code in the panel
        const addedItemPanel = document.getElementById('addedItemPanel');
            addedItemPanel.innerHTML = `
                <div style="width: 100%; max-width: 400px; text-align: center; font-family: Arial, sans-serif; color: #374151;">
                    <h3 style="font-weight: 600; font-size: 1.25rem; margin-bottom: 12px; padding-bottom: 6px;">Added Item Details</h3>
                    <p style="margin: 8px 0;"><strong>Item Name:</strong> ${itemName}</p>
                    <p style="margin: 8px 0;"><strong>Department:</strong> ${department}</p>
                    <p style="margin: 8px 0;"><strong>Category:</strong> ${category}</p>
                    <p style="margin: 8px 0 16px 0;"><strong>Description:</strong> ${description}</p>
                    <img src="${url}" alt="QR Code" style="border: 2px solid #3b82f6; border-radius: 8px; margin: 0 auto 20px auto; display: block; width: 200px; height: 200px;" />
                    <form method="POST" action="{{ route('inventory.confirm') }}" id="confirmForm">
                        @csrf
                        <input type="hidden" name="item_name" value="${itemName}">
                        <input type="hidden" name="department" value="${department}">
                        <input type="hidden" name="category_id" value="${category}">
                        <input type="hidden" name="description" value="${description}">
                        <button type="submit" class="btn btn-success" id="confirmButton" style="padding: 10px 20px; font-size: 1rem; border-radius: 6px; transition: background-color 0.3s ease;">Confirm</button>
                    </form>
                </div>
            `;

            // Add event listener to disable confirm button on submit to prevent multiple submissions
            const confirmForm = document.getElementById('confirmForm');
            const confirmButton = document.getElementById('confirmButton');
            confirmForm.addEventListener('submit', function(event) {
                confirmButton.disabled = true;
                confirmButton.innerText = 'Submitting...';
            });

            // Fade in animation for added item panel
            addedItemPanel.style.opacity = 0;
            setTimeout(() => {
                addedItemPanel.style.opacity = 1;
            }, 50);
    });
});
</script>

<script>
// Make success notification disappear after 1 second
window.addEventListener('DOMContentLoaded', (event) => {
    const successNotification = document.getElementById('successNotification');
    if (successNotification) {
        setTimeout(() => {
            successNotification.style.transition = 'opacity 0.5s ease';
            successNotification.style.opacity = '0';
            setTimeout(() => {
                successNotification.remove();
            }, 500);
        }, 1000);
    }
});
</script>

<script>
// Make success notification disappear after 1 second
window.addEventListener('DOMContentLoaded', (event) => {
    const successNotification = document.getElementById('successNotification');
    if (successNotification) {
        setTimeout(() => {
            successNotification.style.transition = 'opacity 0.5s ease';
            successNotification.style.opacity = '0';
            setTimeout(() => {
                successNotification.remove();
            }, 500);
        }, 1000);
    }
});
</script>

<script>
// Make success notification disappear after 1 second
window.addEventListener('DOMContentLoaded', (event) => {
    const successNotification = document.getElementById('successNotification');
    if (successNotification) {
        setTimeout(() => {
            successNotification.style.transition = 'opacity 0.5s ease';
            successNotification.style.opacity = '0';
            setTimeout(() => {
                successNotification.remove();
            }, 500);
        }, 1000);
    }
});
</script>

<script>
// Make success notification disappear after 1 second
window.addEventListener('DOMContentLoaded', (event) => {
    const successNotification = document.getElementById('successNotification');
    if (successNotification) {
        setTimeout(() => {
            successNotification.style.transition = 'opacity 0.5s ease';
            successNotification.style.opacity = '0';
            setTimeout(() => {
                successNotification.remove();
            }, 500);
        }, 1000);
    }
});
</script>

</x-main-layout>
