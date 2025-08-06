$(document).ready(function() {
    // Check if sidebar-collapse exists before attempting to modify it
    const sidebarCollapse = document.getElementById('sidebar-collapse');
    if (sidebarCollapse && $(sidebarCollapse).hasClass('slimScrollDiv')) {
        $(sidebarCollapse).slimScroll({destroy: true}).css({overflow: 'visible', height: 'auto'});
    }
});

// Scanner form submission - only run if scannerForm exists
document.addEventListener('DOMContentLoaded', function() {
    const scannerForm = document.getElementById('scannerForm');
    const submitButton = document.getElementById('submitCheckedUnitsButton');
    
    if (!scannerForm || !submitButton) {
        console.log('Scanner form elements not found - skipping scanner form initialization');
        return;
    }

    scannerForm.addEventListener('submit', function(event) {
        event.preventDefault();
        
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
                
                if (successMessage && successMessageText) {
                    successMessageText.textContent = data.message || 'Checked units submitted successfully.';
                    successMessage.style.display = 'block';

                    setTimeout(() => {
                        successMessage.style.transition = 'opacity 0.5s ease';
                        successMessage.style.opacity = '0';
                        setTimeout(() => {
                            successMessage.style.display = 'none';
                            successMessage.style.opacity = '1';
                        }, 500);
                    }, 2000);
                }
                
                submitButton.innerText = 'Submitted';
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
});

// Modal function - only run if showLegendBtn exists
document.addEventListener('DOMContentLoaded', function() {
    const showLegendBtn = document.getElementById('showLegendBtn');
    const statusLegendModal = document.getElementById('statusLegendModal');
    
    if (showLegendBtn && statusLegendModal) {
        showLegendBtn.addEventListener('click', function() {
            var legendModal = new bootstrap.Modal(statusLegendModal);
            legendModal.show();
        });
    }
});



