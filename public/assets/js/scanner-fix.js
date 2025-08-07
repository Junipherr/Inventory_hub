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
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = document.querySelector('input[name="_token"]').value;
        document.head.appendChild(meta);
    }
});
