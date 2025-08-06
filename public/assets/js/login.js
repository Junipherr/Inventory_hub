/**
 * Enhanced Login Form JavaScript
 * School Inventory Management System
 */

class LoginForm {
    constructor() {
        this.form = document.getElementById('login-form');
        this.emailInput = document.getElementById('login');
        this.passwordInput = document.getElementById('password');
        this.submitButton = document.getElementById('login-button');
        this.loadingSpinner = document.getElementById('loading-spinner');
        this.buttonText = document.getElementById('button-text');
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupValidation();
        this.setupPasswordToggle();
        this.setupAutoFocus();
    }

    setupEventListeners() {
        // Form submission
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));
        
        // Real-time validation
        this.emailInput.addEventListener('blur', () => this.validateEmail());
        this.emailInput.addEventListener('input', () => this.clearError('login'));
        
        this.passwordInput.addEventListener('blur', () => this.validatePassword());
        this.passwordInput.addEventListener('input', () => this.clearError('password'));
        
        // Enter key handling
        this.passwordInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.handleSubmit(e);
            }
        });
    }

    setupValidation() {
        // Add validation classes
        this.emailInput.classList.add('validation-input');
        this.passwordInput.classList.add('validation-input');
    }

    setupPasswordToggle() {
        const passwordToggle = document.getElementById('password-toggle');
        passwordToggle.addEventListener('click', () => {
            const type = this.passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            this.passwordInput.setAttribute('type', type);
            passwordToggle.classList.toggle('fa-eye');
            passwordToggle.classList.toggle('fa-eye-slash');
        });
    }

    setupAutoFocus() {
        // Focus on email field if empty
        if (!this.emailInput.value) {
            this.emailInput.focus();
        }
    }

    validateEmail() {
        const email = this.emailInput.value.trim();
        const errorElement = document.getElementById('login-error');
        
        if (!email) {
            this.showError('login', 'Email or username is required');
            return false;
        }
        
        // Basic email validation if input contains @
        if (email.includes('@')) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                this.showError('login', 'Please enter a valid email address');
                return false;
            }
        }
        
        this.clearError('login');
        return true;
    }

    validatePassword() {
        const password = this.passwordInput.value;
        
        if (!password) {
            this.showError('password', 'Password is required');
            return false;
        }
        
        if (password.length < 6) {
            this.showError('password', 'Password must be at least 6 characters');
            return false;
        }
        
        this.clearError('password');
        return true;
    }

    showError(field, message) {
        const errorElement = document.getElementById(`${field}-error`);
        const inputElement = document.getElementById(field === 'login' ? 'login' : 'password');
        
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        inputElement.classList.add('error');
    }

    clearError(field) {
        const errorElement = document.getElementById(`${field}-error`);
        const inputElement = document.getElementById(field === 'login' ? 'login' : 'password');
        
        errorElement.style.display = 'none';
        inputElement.classList.remove('error');
    }

    async handleSubmit(e) {
        e.preventDefault();
        
        // Validate form
        const isEmailValid = this.validateEmail();
        const isPasswordValid = this.validatePassword();
        
        if (!isEmailValid || !isPasswordValid) {
            return;
        }
        
        // Show loading state
        this.setLoadingState(true);
        
        try {
            // Submit form
            this.form.submit();
        } catch (error) {
            console.error('Login error:', error);
            this.setLoadingState(false);
            this.showError('login', 'An error occurred. Please try again.');
        }
    }

    setLoadingState(loading) {
        if (loading) {
            this.submitButton.disabled = true;
            this.loadingSpinner.style.display = 'inline-block';
            this.buttonText.textContent = 'Signing In...';
            this.submitButton.classList.add('btn-loading');
        } else {
            this.submitButton.disabled = false;
            this.loadingSpinner.style.display = 'none';
            this.buttonText.textContent = 'Sign In';
            this.submitButton.classList.remove('btn-loading');
        }
    }

    // Utility method to shake animation for errors
    shake(element) {
        element.style.animation = 'shake 0.5s';
        setTimeout(() => {
            element.style.animation = '';
        }, 500);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new LoginForm();
});

// Add shake animation
const style = document.createElement('style');
style.textContent = `
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
`;
document.head.appendChild(style);

// Handle browser back button
window.addEventListener('pageshow', (event) => {
    if (event.persisted) {
        // Reset form state when returning via back button
        const loginForm = new LoginForm();
        loginForm.setLoadingState(false);
    }
});

// Prevent form resubmission on page refresh
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// Add keyboard shortcuts
document.addEventListener('keydown', (e) => {
    // Ctrl/Cmd + Enter to submit form
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        const form = document.getElementById('login-form');
        if (form) {
            form.dispatchEvent(new Event('submit'));
        }
    }
    
    // Escape to clear form
    if (e.key === 'Escape') {
        const form = document.getElementById('login-form');
        if (form) {
            form.reset();
            const loginForm = new LoginForm();
            loginForm.clearError('login');
            loginForm.clearError('password');
        }
    }
});

// Add focus trap for accessibility
class FocusTrap {
    constructor(element) {
        this.element = element;
        this.focusableElements = element.querySelectorAll(
            'input, button, [tabindex]:not([tabindex="-1"])'
        );
        this.firstFocusable = this.focusableElements[0];
        this.lastFocusable = this.focusableElements[this.focusableElements.length - 1];
        
        this.init();
    }

    init() {
        this.element.addEventListener('keydown', (e) => this.handleKeydown(e));
    }

    handleKeydown(e) {
        if (e.key === 'Tab') {
            if (e.shiftKey) {
                if (document.activeElement === this.firstFocusable) {
                    e.preventDefault();
                    this.lastFocusable.focus();
                }
            } else {
                if (document.activeElement === this.lastFocusable) {
                    e.preventDefault();
                    this.firstFocusable.focus();
                }
            }
        }
    }
}

// Initialize focus trap
document.addEventListener('DOMContentLoaded', () => {
    const loginContainer = document.querySelector('.login-container');
    if (loginContainer) {
        new FocusTrap(loginContainer);
    }
});

// Add performance monitoring
if ('performance' in window) {
    window.addEventListener('load', () => {
        const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
        console.log(`Login page loaded in ${loadTime}ms`);
    });
}

// Add offline detection
window.addEventListener('online', () => {
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(el => el.style.display = 'none');
});

window.addEventListener('offline', () => {
    const loginForm = new LoginForm();
    loginForm.showError('login', 'No internet connection. Please check your network.');
});
