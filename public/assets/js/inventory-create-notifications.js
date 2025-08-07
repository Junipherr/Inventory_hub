document.addEventListener('DOMContentLoaded', function() {
    // Configuration
    const config = {
        totalSteps: 3,
        previewDelay: 300
    };

    // DOM Elements
    const elements = {
        form: document.getElementById('inventoryForm'),
        steps: document.querySelectorAll('.form-step'),
        prevBtn: document.getElementById('prevBtn'),
        nextBtn: document.getElementById('nextBtn'),
        submitBtn: document.getElementById('submitBtn'),
        progressBar: document.getElementById('progressBar')
    };

    // State
    let currentStep = 1;

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
        document.querySelectorAll('[required]').forEach(input => {
            input.addEventListener('blur', function() {
                if (!this.value.trim()) {
                    this.classList.add('is-invalid');
                } else {
                    this.classList.remove('is-invalid');
                }
            });
        });
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
        if (elements.submitBtn) {
            elements.submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
            elements.submitBtn.disabled = true;
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
                // Show success notification
                showSuccessMessage(data.message || 'Item added successfully!');
                
                // Reset form and go to step 1
                elements.form.reset();
                currentStep = 1;
                showStep(1);
                
                // Enable submit button
                if (elements.submitBtn) {
                    elements.submitBtn.innerHTML = '<i class="fa fa-check"></i> Confirm & Add Item';
                    elements.submitBtn.disabled = false;
                }
                
                // Redirect after showing notification
                setTimeout(() => {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.href = '/inventory/create';
                    }
                }, 2000);
            } else {
                showErrorMessage(data.message || 'Failed to add item');
                if (elements.submitBtn) {
                    elements.submitBtn.innerHTML = '<i class="fa fa-check"></i> Confirm & Add Item';
                    elements.submitBtn.disabled = false;
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showErrorMessage('An error occurred while submitting the form');
            if (elements.submitBtn) {
                elements.submitBtn.innerHTML = '<i class="fa fa-check"></i> Confirm & Add Item';
                elements.submitBtn.disabled = false;
            }
        });
    }

    // Utility Functions
    function changeStep(direction) {
        const newStep = currentStep + direction;

        if (newStep < 1 || newStep > config.totalSteps) return;

        if (direction > 0 && !validateStep(currentStep)) {
            return;
        }

        currentStep = newStep;
        showStep(currentStep);
    }

    // Notification Functions
    function showSuccessMessage(message) {
        const successDiv = document.getElementById('dynamicSuccessMessage');
        const messageText = document.getElementById('successMessageText');
        
        if (successDiv && messageText) {
            messageText.textContent = message;
            successDiv.style.display = 'block';
            
            setTimeout(() => {
                successDiv.style.display = 'none';
            }, 5000);
        } else {
            // Fallback to alert if dynamic elements not found
            alert(message);
        }
    }

    function showErrorMessage(message) {
        const errorDiv = document.getElementById('dynamicErrorMessage');
        const errorText = document.getElementById('errorMessageText');
        
        if (errorDiv && errorText) {
            errorText.textContent = message;
            errorDiv.style.display = 'block';
            
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        } else {
            // Fallback to alert if dynamic elements not found
            alert('Error: ' + message);
        }
    }

    // Legacy error display (for backward compatibility)
    function showError(message) {
        showErrorMessage(message);
    }
});
