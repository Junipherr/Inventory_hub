$(document).ready(function() {
    if ($('#sidebar-collapse').hasClass('slimScrollDiv')) {
        $('#sidebar-collapse').slimScroll({destroy: true}).css({overflow: 'visible', height: 'auto'});
    }
});

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


//Modal Function
document.getElementById('showLegendBtn').addEventListener('click', function() {
    var legendModal = new bootstrap.Modal(document.getElementById('statusLegendModal'));
    legendModal.show();
});



