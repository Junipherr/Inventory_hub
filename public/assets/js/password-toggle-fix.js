/**
 * Password Toggle Fix - Enhanced password visibility toggle functionality
 * Fixes common issues with password show/hide icons in login forms
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize password toggle functionality
    initializePasswordToggle();
});

/**
 * Initialize password visibility toggle for all password fields
 */
function initializePasswordToggle() {
    // Find all password toggle buttons
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    passwordToggles.forEach(toggle => {
        if (toggle) {
            toggle.addEventListener('click', function() {
                const passwordInput = this.previousElementSibling;
                
                if (passwordInput && passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                }
            });
        }
    });
}

/**
 * Alternative implementation for password toggle with better error handling
 */
function setupPasswordToggle(inputId, toggleId) {
    const passwordInput = document.getElementById(inputId);
    const toggleButton = document.getElementById(toggleId);
    
    if (!passwordInput || !toggleButton) {
        console.warn('Password toggle elements not found');
        return;
    }
    
    toggleButton.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
    });
}

// Export for global use
window.PasswordToggle = {
    initializePasswordToggle,
    setupPasswordToggle
};
