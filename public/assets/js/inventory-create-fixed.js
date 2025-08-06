document.addEventListener('DOMContentLoaded', function() {
    // Configuration
    const config = {
        totalSteps: 3,
        apiEndpoint: '/api/inventory/generate-qr',
        previewDelay: 300
    };

    // DOM Elements
    const elements = {
        form: document.getElementById('inventoryForm'),
        steps: document.querySelectorAll('.form-step'),
        prevBtn: document.getElementById('prevBtn'),
        nextBtn: document.getElementById('nextBtn'),
        submitBtn: document.getElementById('submitBtn'),
        progressBar: document.getElementById('progressBar'),
        livePreview: document.getElementById('livePreview'),
        qrPreview: document.getElementById('qrPreview'),
        qrCodeInput: document.getElementById('qrCodeInput')
    };

    // State
    let currentStep = 1;
    let previewTimeout;

    // Initialize
    init();

    function init() {
        showStep(1);
        attachEventListeners();
        setupValidation();
    }

    // Navigation Functions
    function showStep(step) {
        if (!elements.steps || elements.steps.length === 0) return;

        elements.steps.forEach((s, index) => {
            s.classList.toggle('d-none', index + 1 !== step);
        });

        // Update buttons
        if (elements.prevBtn) {
            elements.prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        }
        if (elements.nextBtn) {
            elements.nextBtn.style.display = step === config.totalSteps ? 'none' : 'inline-block';
        }
        if (elements.submitBtn) {
            elements.submitBtn.classList.toggle('d-none', step !== config.totalSteps);
        }

        // Update progress
        updateProgress(step);

        // Special handling for step 3
        if (step === 3) {
            updatePreview();
            generateQRCode();
        }
    }

    function updateProgress(step) {
        if (!elements.progressBar) return;

        const progress = ((step - 1) / (config.totalSteps - 1)) * 100;
        elements.progressBar.style.width = progress + '%';
        elements.progressBar.textContent = `Step ${step} of ${config.totalSteps}`;
    }

    // Validation
    function validateStep(step) {
        const currentStepElement = document.getElementById(`step${step}`);
        if (!currentStepElement) return true;

        const inputs = currentStepElement.querySelectorAll('[required]');
        let isValid = true;

        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
            }
        });

        return isValid;
    }

    function setupValidation() {
        document.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        });
    }

    // Live Preview
    function updatePreview() {
        if (!elements.livePreview) return;

        const itemName = document.getElementById('item_name')?.value || '';
        const category = document.getElementById('categorySelect')?.options[document.getElementById('categorySelect').selectedIndex]?.text || '';
        const room = document.getElementById('roomSelect')?.options[document.getElementById('roomSelect').selectedIndex]?.text || '';
        const quantity = document.getElementById('quantity')?.value || '';

        // Update summary
        const summaryElements = {
            summaryItemName: itemName || '-',
            summaryCategory: category || '-',
            summaryRoom: room || '-',
            summaryQuantity: quantity || '-'
        };

        Object.entries(summaryElements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) element.textContent = value;
        });

        // Update live preview
        clearTimeout(previewTimeout);
        previewTimeout = setTimeout(() => {
            if (itemName) {
                elements.livePreview.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">${itemName}</h6>
                            <p class="text-muted">
                                ${category} • ${room} • ${quantity} units
                            </p>
                        </div>
                    </div>
                `;
            } else {
                elements.livePreview.innerHTML = `
                    <div class="text-center text-muted py-5">
                        <i class="fa fa-info-circle fa-3x mb-3"></i>
                        <p>Fill in the form to see live preview</p>
                    </div>
                `;
            }
        }, config.previewDelay);
    }

    // QR Code Generation
    function generateQRCode() {
        if (!elements.qrPreview || !elements.qrCodeInput) return;

        const itemName = document.getElementById('item_name')?.value;
        const roomId = document.getElementById('roomSelect')?.value;

        if (itemName && roomId) {
            const qrData = `${itemName}-${Date.now()}`;
            const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(qrData)}&size=200x200`;

            elements.qrPreview.src = qrUrl;
            elements.qrCodeInput.value = qrData;
        }
    }

    // Event Listeners
    function attachEventListeners() {
        // Navigation buttons
        if (elements.prevBtn) {
            elements.prevBtn.addEventListener('click', () => changeStep(-1));
        }
        if (elements.nextBtn) {
            elements.nextBtn.addEventListener('click', () => changeStep(1));
        }

        // Form inputs for live preview
        const previewInputs = ['item_name', 'categorySelect', 'roomSelect', 'quantity'];
        previewInputs.forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener('input', updatePreview);
                element.addEventListener('change', updatePreview);
            }
        });

        // Form submission
        if (elements.form) {
            elements.form.addEventListener('submit', handleSubmit);
        }
    }

    // Form Submission
    function handleSubmit(e) {
        e.preventDefault();

        if (!validateStep(config.totalSteps)) {
            return;
        }

        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
            submitBtn.disabled = true;
        }

        // Submit via AJAX
        const formData = new FormData(elements.form);

        fetch(elements.form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccessModal();
                resetForm();
            } else {
                showError(data.message || 'Failed to add item');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred while submitting the form');
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fa fa-check"></i> Confirm & Add Item';
                submitBtn.disabled = false;
            }
        });
    }

    // Utility Functions
    function changeStep(direction) {
        const newStep = currentStep + direction;

        if (newStep < 1 || newStep > config.totalSteps) return;

        // Only validate when moving forward
        if (direction > 0 && !validateStep(currentStep)) {
            return;
        }

        currentStep = newStep;
        showStep(currentStep);
    }

    // Helper functions for modal and form reset
    function showSuccessModal() {
        // Create and show success modal
        const modal = document.createElement('div');
        modal.className = 'modal fade show';
        modal.style.display = 'block';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Success!</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Item has been successfully added to inventory.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Auto-hide after 3 seconds
        setTimeout(() => {
            modal.remove();
        }, 3000);
    }

    function showError(message) {
        // Create and show error modal
        const modal = document.createElement('div');
        modal.className = 'modal fade show';
        modal.style.display = 'block';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Error</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>${message}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }

    function resetForm() {
        if (elements.form) {
            elements.form.reset();
            currentStep = 1;
            showStep(1);
            
            // Reset validation states
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
        }
    }
});
