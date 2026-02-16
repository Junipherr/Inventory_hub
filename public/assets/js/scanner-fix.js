/**
 * Scanner Form Submission Fix
 * This file contains the enhanced form submission handler for the scanner page
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get form and submit button elements
    const scannerForm = document.getElementById('scannerForm');
    const submitBtn = document.getElementById('submitBtn');

    if (!scannerForm || !submitBtn) {
        console.error('Scanner form or submit button not found');
        return;
    }

    // Enhanced form submission handler
    scannerForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const submitButton = submitBtn;
        const originalText = submitButton.innerHTML;

        // Disable button and show loading state
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

        // Clear any previous error messages
        const errorDiv = document.getElementById('errorMessage');
        if (errorDiv) {
            errorDiv.style.display = 'none';
        }

        // Collect form data
        const formData = new FormData(scannerForm);

        // Submit form via AJAX
        fetch(scannerForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                showSuccessMessage(data.message || 'Changes saved successfully!');

                // Reset button
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;

                // Optional: Refresh page or update UI
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to save changes');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage(error.message || 'Failed to save changes. Please try again.');

            // Reset button
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        });
    });

    // Helper functions for messages
    function showSuccessMessage(message) {
        const successDiv = document.createElement('div');
       // successDiv.className = 'alert alert-success alert-dismissible fade show';
        // successDiv.innerHTML = `
        //     <strong>Success!</strong> ${message}
        //     <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        // `;

        const submitSection = document.querySelector('.submit-section');
        if (submitSection) {
            submitSection.insertBefore(successDiv, submitSection.firstChild);

            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        }
    }

    function showErrorMessage(message) {
        const errorDiv = document.getElementById('errorMessage');
        if (errorDiv) {
            errorDiv.querySelector('#errorText').textContent = message;
            errorDiv.style.display = 'block';

            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }
    }

    // Add CSRF token to meta tag if not exists
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const tokenInput = document.querySelector('input[name="_token"]');
        if (tokenInput) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = tokenInput.value;
            document.head.appendChild(meta);
        }
    }

    // Item details modal functionality - check if modal elements exist
    const itemInfoModalElement = document.getElementById('itemInfoModal');
    if (itemInfoModalElement) {
        const itemInfoModal = new bootstrap.Modal(itemInfoModalElement);
        const modalItemName = document.getElementById('modalItemName');
        const modalRoom = document.getElementById('modalRoom');
        const modalCategory = document.getElementById('modalCategory');
        const modalQuantity = document.getElementById('modalQuantity');
        const modalStatus = document.getElementById('modalStatus');
        const modalPersonInCharge = document.getElementById('modalPersonInCharge');
        const modalDescription = document.getElementById('modalDescription');

        // View item details - only if all required elements exist
        const viewDetailsButtons = document.querySelectorAll('.view-details');
        if (viewDetailsButtons.length > 0 && modalItemName && modalRoom && modalCategory) {
            viewDetailsButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Store reference to the clicked button
                    const clickedButton = this;
                    
                    const row = clickedButton.closest('tr');
                    const itemName = row.dataset.itemName;
                    const room = row.dataset.room;
                    const category = row.dataset.category;
                    const quantity = row.dataset.itemQuantity;
                    const description = row.dataset.itemDescription;
                    const qrCode = row.dataset.itemQr;
                    const personInCharge = row.dataset.personInCharge;

                    modalItemName.textContent = itemName;
                    modalRoom.textContent = room;
                    modalCategory.textContent = category;
                    if (modalQuantity) modalQuantity.textContent = quantity;
                    if (modalDescription) modalDescription.textContent = description || 'No description available';
                    if (modalPersonInCharge) modalPersonInCharge.textContent = personInCharge;

                    // Update QR code dynamically
                    updateModalQRCode(qrCode, itemName);

                    // Remove focus from button before showing modal to avoid aria-hidden accessibility issue
                    clickedButton.blur();
                    
                    itemInfoModal.show();
                });
            });
        }
    }

    // Function to update QR code in modal
    function updateModalQRCode(qrCode, itemName) {
        const qrImage = document.getElementById('modalQRImage');
        const qrLoading = document.getElementById('qrLoading');
        const modalQRText = document.getElementById('modalQRText');

        // Check if required elements exist
        if (!qrImage || !qrLoading || !modalQRText) {
            console.warn('QR code display elements not found');
            return;
        }

        if (qrCode && qrCode !== 'N/A') {
            // Show loading
            qrLoading.style.display = 'block';
            qrImage.style.display = 'none';
            modalQRText.textContent = qrCode;

            // Generate QR code via AJAX
            fetch(`/inventory/qrcode/${encodeURIComponent(qrCode)}`)
                .then(response => response.text())
                .then(svgText => {
                    const svgBlob = new Blob([svgText], { type: 'image/svg+xml' });
                    const url = URL.createObjectURL(svgBlob);
                    qrImage.src = url;
                    qrImage.alt = `QR Code for ${itemName}`;
                    qrImage.style.display = 'block';
                    qrLoading.style.display = 'none';
                })
                .catch(error => {
                    console.error('Error loading QR code:', error);
                    qrImage.src = '';
                    qrImage.alt = 'Error loading QR Code';
                    qrImage.style.display = 'none';
                    qrLoading.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Error loading QR Code';
                    qrLoading.style.display = 'block';
                });
        } else {
            qrImage.src = '';
            qrImage.alt = 'No QR Code available';
            qrImage.style.display = 'none';
            qrLoading.innerHTML = '<i class="fas fa-info-circle"></i> No QR Code available';
            qrLoading.style.display = 'block';
            modalQRText.textContent = 'N/A';
        }
    }
});
