<x-main-layout>
<div class="page-heading">
        <div id="dynamicSuccessMessage" style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-success">
                <strong>Success!</strong> <span id="successMessageText"></span>
            </div>
        </div>
        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title"><h1>QR Scanner - Items List</h1></div>
                </div>
                @if($items->isEmpty())
                    <p>No items found.</p>
                @else
                    <form method="POST" action="{{ route('scanner.update') }}" id="scannerForm">
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
                        <button type="submit" class="btn btn-primary" id="submitCheckedUnitsButton">Submit Checked Units</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

<script>
document.getElementById('scannerForm').addEventListener('submit', function(event) {
    event.preventDefault();
    const submitButton = document.getElementById('submitCheckedUnitsButton');
    submitButton.disabled = true;
    submitButton.innerText = 'Submitting...';

    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action, {
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
            const successMessage = document.getElementById('dynamicSuccessMessage');
            const successMessageText = document.getElementById('successMessageText');
            successMessageText.textContent = data.message || 'Checked units submitted successfully.';
            successMessage.style.display = 'block';

            submitButton.innerText = 'Submitted';

            setTimeout(() => {
                successMessage.style.transition = 'opacity 0.5s ease';
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                    successMessage.style.opacity = '1';
                }, 500);
            }, 2000);
        } else {
            alert(data.message || 'Failed to submit checked units.');
            submitButton.disabled = false;
            submitButton.innerText = 'Submit Checked Units';
        }
    })
    .catch(error => {
        alert('Error submitting checked units: ' + error.message);
        submitButton.disabled = false;
        submitButton.innerText = 'Submit Checked Units';
    });
});
</script>
</x-main-layout>
