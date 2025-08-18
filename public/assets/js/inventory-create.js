/**
 * Fixed Inventory Create JavaScript
 * Handles QR code generation and form submission
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('Inventory Create JS loaded');
    
    const form = document.getElementById('inventoryForm');
    if (!form) {
        console.error('Form not found');
        return;
    }

    // Initialize QR code generation
    initializeQRGeneration();

    // Form submission handler
    form.addEventListener('submit', handleFormSubmit);

    // Add live preview listeners
    addLivePreviewListeners();
});

/**
 * Initialize QR code generation functionality
 */
function initializeQRGeneration() {
    // Check if QRCode library is available
    if (typeof QRCode === 'undefined') {
        console.error('QRCode library not loaded');
        loadQRCodeLibrary();
    }
}

/**
 * Load QRCode library if not available
 */
function loadQRCodeLibrary() {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js';
    script.onload = function() {
        console.log('QRCode library loaded successfully');
    };
    script.onerror = function() {
        console.error('Failed to load QRCode library');
    };
    document.head.appendChild(script);
}

/**
 * Handle form submission
 */
function handleFormSubmit(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const formData = new FormData(this);
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding Item...';

    // Generate QR code data
    const qrData = generateQRData();
    formData.set('qr_code', qrData);

    fetch('/inventory', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            handleSuccess(data);
        } else {
            handleError(data.message || 'Error adding item');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        handleError('Network error occurred');
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fa fa-check"></i> Confirm & Add Item';
    });
}

/**
 * Generate QR code data
 */
function generateQRData() {
    const itemName = document.getElementById('item_name')?.value || '';
    const category = document.getElementById('categorySelect')?.value || '';
    const room = document.getElementById('roomSelect')?.value || '';
    const quantity = document.getElementById('quantity')?.value || '1';
    
    // Create unique identifier
    const timestamp = Date.now();
    const uniqueId = `ITEM-${timestamp}`;
    
    return uniqueId;
}

/**
 * Generate QR code for preview
 */
function generateQRPreview() {
    const qrContainer = document.getElementById('qrPreview');
    if (!qrContainer) return;

    const qrData = generateQRData();
    
    // Clear previous QR code
    qrContainer.innerHTML = '';
    
    try {
        new QRCode(qrContainer, {
            text: qrData,
            width: 200,
            height: 200,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    } catch (error) {
        console.error('QR generation error:', error);
        qrContainer.innerHTML = '<p class="text-danger">Error generating QR code</p>';
    }
}

/**
 * Handle successful form submission
 */
function handleSuccess(data) {
    showSuccessMessage(data.message);
    
    // Removed success modal display as per request
    
    // Reset form
    document.getElementById('inventoryForm').reset();
    
    // Redirect after delay
    setTimeout(() => {
        window.location.href = '/inventory/items';
    }, 2000);
}

/**
 * Handle errors
 */
function handleError(message) {
    showErrorMessage(message);
}

/**
 * Show success message
 */
function showSuccessMessage(message) {
    const successDiv = document.getElementById('dynamicSuccessMessage');
    const messageText = document.getElementById('successMessageText');
    
    if (successDiv && messageText) {
        messageText.textContent = message;
        successDiv.style.display = 'block';
        
        setTimeout(() => {
            successDiv.style.display = 'none';
        }, 3000);
    }
}

/**
 * Show error message
 */
function showErrorMessage(message) {
    const errorDiv = document.getElementById('dynamicErrorMessage');
    const errorText = document.getElementById('errorMessageText');
    
    if (errorDiv && errorText) {
        errorText.textContent = message;
        errorDiv.style.display = 'block';
        
        setTimeout(() => {
            errorDiv.style.display = 'none';
        }, 5000);
    }
}

/**
 * Add live preview listeners
 */
function addLivePreviewListeners() {
    const formInputs = document.querySelectorAll('#inventoryForm input, #inventoryForm select, #inventoryForm textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', updateLivePreview);
        input.addEventListener('change', updateLivePreview);
    });
}

/**
 * Update live preview
 */
function updateLivePreview() {
    const itemName = document.getElementById('item_name')?.value || '';
    const category = document.getElementById('categorySelect')?.selectedOptions[0]?.text || '';
    const room = document.getElementById('roomSelect')?.selectedOptions[0]?.text || '';
    const quantity = document.getElementById('quantity')?.value || '0';
    
    // Update live preview panel
    const previewHTML = `
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">${itemName || 'Item Name'}</h5>
                <p><strong>Category:</strong> ${category}</p>
                <p><strong>Room:</strong> ${room}</p>
                <p><strong>Quantity:</strong> ${quantity} units</p>
            </div>
        </div>
    `;
    
    const livePreview = document.getElementById('livePreview');
    if (livePreview) {
        livePreview.innerHTML = previewHTML;
    }
    
    // Update item summary in step 3
    const summaryItemName = document.getElementById('summaryItemName');
    const summaryCategory = document.getElementById('summaryCategory');
    const summaryRoom = document.getElementById('summaryRoom');
    const summaryQuantity = document.getElementById('summaryQuantity');
    
    if (summaryItemName) summaryItemName.textContent = itemName || '-';
    if (summaryCategory) summaryCategory.textContent = category || '-';
    if (summaryRoom) summaryRoom.textContent = room || '-';
    if (summaryQuantity) summaryQuantity.textContent = quantity ? quantity + ' units' : '-';
}

// Global functions for step navigation
window.changeStep = function(direction) {
    const currentStep = document.querySelector('.form-step:not(.d-none)');
    const currentStepNum = parseInt(currentStep.id.replace('step', ''));
    const nextStepNum = currentStepNum + direction;
    
    if (nextStepNum < 1 || nextStepNum > 3) return;
    
    if (direction > 0 && !validateStep(currentStepNum)) return;
    
    currentStep.classList.add('d-none');
    document.getElementById(`step${nextStepNum}`).classList.remove('d-none');
    
    updateButtons(nextStepNum);
    updateProgressBar(nextStepNum);
    
    // Generate QR code when reaching step 3
    if (nextStepNum === 3) {
        generateQRPreview();
        updateLivePreview();
    }
};

window.generateQRPreview = generateQRPreview;
window.updateSummary = updateLivePreview;

/**
 * Validate current step
 */
function validateStep(step) {
    const fields = document.querySelectorAll(`#step${step} [required]`);
    let valid = true;
    
    fields.forEach(field => {
        if (!field.value.trim()) {
            valid = false;
            field.classList.add('is-invalid');
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    return valid;
}

/**
 * Update progress bar
 */
function updateProgressBar(step) {
    const progress = ((step - 1) / 2) * 100;
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = `${progress}%`;
        progressBar.textContent = `Step ${step} of 3`;
    }
}

/**
 * Update navigation buttons
 */
function updateButtons(step) {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    if (prevBtn) prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
    if (nextBtn) nextBtn.style.display = step === 3 ? 'none' : 'inline-block';
    if (submitBtn) submitBtn.classList.toggle('d-none', step !== 3);
}
