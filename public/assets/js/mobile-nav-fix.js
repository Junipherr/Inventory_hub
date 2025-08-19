/**
 * Mobile Navigation Fix
 * Prevents auto-collapse and fixes positioning issues
 */

document.addEventListener('DOMContentLoaded', function() {
    const mobileNavBtn = document.querySelector('[data-bs-target="#mobileNavMenu"]');
    const mobileNavMenu = document.getElementById('mobileNavMenu');
    
    if (!mobileNavBtn || !mobileNavMenu) {
        console.warn('Mobile navigation elements not found');
        return;
    }
    
    // Prevent auto-collapse on mobile devices
    let isMenuOpen = false;
    
    // Handle menu toggle
    mobileNavBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        e.preventDefault();
        isMenuOpen = !isMenuOpen;
        
        if (isMenuOpen) {
            // Add backdrop
            createBackdrop();
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
            // Ensure menu is visible
            mobileNavMenu.classList.add('show');
            mobileNavMenu.style.display = 'block';
        } else {
            closeMenu();
        }
    });
    
    // Prevent clicks inside menu from closing it
    mobileNavMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (isMenuOpen && !mobileNavMenu.contains(e.target) && !mobileNavBtn.contains(e.target)) {
            closeMenu();
        }
    });
    
    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isMenuOpen) {
            closeMenu();
        }
    });
    
    // Close menu on window resize (if switching to desktop)
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && isMenuOpen) {
            closeMenu();
        }
    });
    
    function closeMenu() {
        isMenuOpen = false;
        mobileNavMenu.classList.remove('show');
        mobileNavMenu.style.display = '';
        removeBackdrop();
        document.body.style.overflow = '';
    }
    
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
            z-index: 1040;
            transition: opacity 0.3s ease;
        `;
        backdrop.addEventListener('click', closeMenu);
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
                backdrop.remove();
            }, 300);
        }
    }
    
    // Fix for Bootstrap collapse events
    mobileNavMenu.addEventListener('shown.bs.collapse', function() {
        isMenuOpen = true;
    });
    
    mobileNavMenu.addEventListener('hidden.bs.collapse', function() {
        isMenuOpen = false;
        removeBackdrop();
        document.body.style.overflow = '';
    });
    
    // Prevent immediate collapse on touch devices
    let touchStartY = 0;
    let touchEndY = 0;
    
    mobileNavMenu.addEventListener('touchstart', function(e) {
        touchStartY = e.changedTouches[0].screenY;
    });
    
    mobileNavMenu.addEventListener('touchend', function(e) {
        touchEndY = e.changedTouches[0].screenY;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const swipeDistance = touchStartY - touchEndY;
        
        // Only close on upward swipe, not downward
        if (swipeDistance > swipeThreshold) {
            closeMenu();
        }
    }
    
    // Fix for backdrop issues with topbar menu
    function fixTopbarBackdrop() {
        // Remove any existing backdrops when page loads
        const existingBackdrops = document.querySelectorAll('.modal-backdrop, .fade');
        existingBackdrops.forEach(backdrop => {
            if (backdrop.style.zIndex === '1040') {
                backdrop.remove();
            }
        });
    }
    
    // Run fix on page load
    fixTopbarBackdrop();
    
    // Also fix when mobile nav is toggled
    mobileNavBtn.addEventListener('click', function() {
        setTimeout(fixTopbarBackdrop, 100);
    });
});
