/**
 * Mobile Navigation Fix
 * Simple toggle functionality like welcome.blade.php
 */

document.addEventListener('DOMContentLoaded', function() {
    const mobileNavBtn = document.querySelector('.mobile-menu-toggle');
    const mobileNavMenu = document.getElementById('mobileNavMenu');
    const mobileNavContent = document.querySelector('.mobile-nav-content');
    const sidebar = document.querySelector('.page-sidebar');
    
    if (!mobileNavBtn || !mobileNavMenu || !sidebar) {
        console.warn('Mobile navigation elements not found');
        return;
    }
    
    // Copy sidebar content to mobile menu and add logout
    function populateMobileMenu() {
        const sidebarContent = sidebar.innerHTML;
        mobileNavContent.innerHTML = sidebarContent;
        
        // Remove any duplicate IDs and fix classes for mobile
        const mobileElements = mobileNavContent.querySelectorAll('[id]');
        mobileElements.forEach(el => {
            el.removeAttribute('id');
        });
        
        // Ensure proper mobile styling
        const adminBlocks = mobileNavContent.querySelectorAll('.admin-block');
        adminBlocks.forEach(block => {
            block.classList.add('mobile-admin-block');
        });
        
        const sideMenus = mobileNavContent.querySelectorAll('.side-menu');
        sideMenus.forEach(menu => {
            menu.classList.add('mobile-side-menu');
        });
        
        // Add logout button to mobile menu
        addLogoutButton();
    }
    
    // Add logout button to mobile menu
    function addLogoutButton() {
        const sideMenus = mobileNavContent.querySelectorAll('.side-menu');
        if (sideMenus.length > 0) {
            const lastMenu = sideMenus[sideMenus.length - 1];
            const logoutItem = document.createElement('li');
            
            // Create form element
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout'; // Laravel's default logout route
            form.id = 'mobile-logout-form';
            
            // Create CSRF token input
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]')?.content || '';
            
            // Create anchor link
            const link = document.createElement('a');
            link.href = '#';
            link.innerHTML = '<i class="sidebar-item-icon fa fa-sign-out-alt"></i><span class="nav-label">Logout</span>';
            link.addEventListener('click', function(e) {
                e.preventDefault();
                form.submit();
            });
            
            // Append elements
            form.appendChild(csrfToken);
            form.appendChild(link);
            logoutItem.appendChild(form);
            lastMenu.appendChild(logoutItem);
        }
    }
    
    // Initialize mobile menu content
    populateMobileMenu();
    
    // Simple toggle functionality like welcome.blade.php with animation
    mobileNavBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        if (mobileNavMenu.classList.contains('mobile-menu-open')) {
            // Close menu
            mobileNavMenu.classList.remove('mobile-menu-open');
            removeBackdrop();
            document.body.classList.remove('mobile-menu-open');
        } else {
            // Open menu
            mobileNavMenu.style.display = "block";
            setTimeout(() => {
                mobileNavMenu.classList.add('mobile-menu-open');
            }, 10);
            createBackdrop();
            document.body.classList.add('mobile-menu-open');
        }
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (mobileNavMenu.style.display === "block" && 
            !mobileNavMenu.contains(e.target) && 
            !mobileNavBtn.contains(e.target)) {
            mobileNavMenu.style.display = "none";
            removeBackdrop();
            document.body.classList.remove('mobile-menu-open');
        }
    });
    
    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && mobileNavMenu.style.display === "block") {
            mobileNavMenu.style.display = "none";
            removeBackdrop();
            document.body.classList.remove('mobile-menu-open');
        }
    });
    
    // Close menu on window resize (if switching to desktop)
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && mobileNavMenu.style.display === "block") {
            mobileNavMenu.style.display = "none";
            removeBackdrop();
            document.body.classList.remove('mobile-menu-open');
        }
    });
    
    function createBackdrop() {
        // Remove any existing backdrops first
        removeBackdrop();
        
        const backdrop = document.createElement('div');
        backdrop.className = 'mobile-nav-backdrop';
        backdrop.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1039;
            transition: opacity 0.3s ease;
        `;
        backdrop.addEventListener('click', function() {
            mobileNavMenu.style.display = "none";
            removeBackdrop();
            document.body.classList.remove('mobile-menu-open');
        });
        document.body.appendChild(backdrop);
        
        // Fade in effect
        setTimeout(() => {
            backdrop.style.opacity = '1';
        }, 10);
    }
    
    function removeBackdrop() {
        const backdrop = document.querySelector('.mobile-nav-backdrop');
        if (backdrop) {
            backdrop.style.opacity = '0';
            setTimeout(() => {
                if (backdrop.parentNode) {
                    backdrop.parentNode.removeChild(backdrop);
                }
            }, 300);
        }
    }
    
    // Handle menu item clicks - close menu after navigation
    mobileNavContent.addEventListener('click', function(e) {
        if (e.target.tagName === 'A' || e.target.closest('a')) {
            // Close menu after a short delay to allow navigation
            setTimeout(function() {
                mobileNavMenu.style.display = "none";
                removeBackdrop();
                document.body.classList.remove('mobile-menu-open');
            }, 300);
        }
    });
    
    // Ensure mobile menu is hidden on page load
    mobileNavMenu.style.display = "none";
});
