/**
 * Dropdown Fix - Enhanced dropdown functionality with null checks
 * This file provides robust dropdown handling for the inventory management system
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all dropdowns with proper error handling
    initializeDropdowns();
    
    // Handle responsive dropdown behavior
    handleResponsiveDropdowns();
    
    // Fix dropdown positioning issues
    fixDropdownPositioning();
});

/**
 * Initialize all dropdown elements with null checks
 */
function initializeDropdowns() {
    // Get all dropdown toggle elements
    const dropdownToggles = document.querySelectorAll('[data-bs-toggle="dropdown"]');
    
    dropdownToggles.forEach(toggle => {
        if (toggle) {
            // Ensure dropdown is properly initialized
            const dropdown = new bootstrap.Dropdown(toggle);
            
            // Add accessibility improvements
            toggle.setAttribute('aria-expanded', 'false');
            
            // Handle dropdown show/hide events
            toggle.addEventListener('show.bs.dropdown', function() {
                this.setAttribute('aria-expanded', 'true');
            });
            
            toggle.addEventListener('hide.bs.dropdown', function() {
                this.setAttribute('aria-expanded', 'false');
            });
        }
    });
}

/**
 * Handle responsive dropdown behavior for mobile devices
 */
function handleResponsiveDropdowns() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const sidebarCollapse = document.querySelector('#sidebar-collapse');
    
    if (navbarToggler) {
        navbarToggler.addEventListener('click', function() {
            const target = document.querySelector(this.getAttribute('data-bs-target'));
            if (target) {
                // Ensure dropdown menus work within collapsed navbar
                const dropdowns = target.querySelectorAll('.dropdown-menu');
                dropdowns.forEach(menu => {
                    menu.style.position = 'static';
                    menu.style.float = 'none';
                });
            }
        });
    }
    
    if (sidebarCollapse) {
        // Handle sidebar dropdown behavior
        const sidebarDropdowns = sidebarCollapse.querySelectorAll('.dropdown');
        sidebarDropdowns.forEach(dropdown => {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            if (toggle && menu) {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Close other open dropdowns
                    sidebarDropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                            if (otherMenu) {
                                otherMenu.classList.remove('show');
                            }
                        }
                    });
                    
                    // Toggle current dropdown
                    menu.classList.toggle('show');
                });
            }
        });
    }
}

/**
 * Fix dropdown positioning issues on scroll and resize
 */
function fixDropdownPositioning() {
    window.addEventListener('resize', function() {
        const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
        openDropdowns.forEach(menu => {
            const toggle = menu.previousElementSibling;
            if (toggle && toggle.classList.contains('dropdown-toggle')) {
                const dropdown = new bootstrap.Dropdown(toggle);
                dropdown.update();
            }
        });
    });
    
    // Handle dropdown positioning in scrollable containers
    const scrollableContainers = document.querySelectorAll('.table-responsive, .scrollable-container');
    scrollableContainers.forEach(container => {
        container.addEventListener('scroll', function() {
            const dropdowns = this.querySelectorAll('.dropdown-menu');
            dropdowns.forEach(menu => {
                if (menu.classList.contains('show')) {
                    const toggle = menu.previousElementSibling;
                    if (toggle && toggle.classList.contains('dropdown-toggle')) {
                        const dropdown = new bootstrap.Dropdown(toggle);
                        dropdown.update();
                    }
                }
            });
        });
    });
}

/**
 * Utility function to safely get element by ID with null check
 */
function safeGetElementById(id) {
    const element = document.getElementById(id);
    if (!element) {
        console.warn(`Element with ID '${id}' not found`);
        return null;
    }
    return element;
}

/**
 * Utility function to safely query selector with null check
 */
function safeQuerySelector(selector, context = document) {
    const element = context.querySelector(selector);
    if (!element) {
        console.warn(`Element with selector '${selector}' not found`);
        return null;
    }
    return element;
}

// Export functions for use in other modules
window.DropdownFix = {
    initializeDropdowns,
    handleResponsiveDropdowns,
    fixDropdownPositioning,
    safeGetElementById,
    safeQuerySelector
};
