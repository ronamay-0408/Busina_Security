(function() {
    "use strict";

    const select = (el, all = false) => {
        el = el.trim();
        return all ? [...document.querySelectorAll(el)] : document.querySelector(el);
    };

    const on = (type, el, listener, all = false) => {
        const elements = select(el, all);
        if (all) {
            elements.forEach(e => e.addEventListener(type, listener));
        } else {
            elements.addEventListener(type, listener);
        }
    };

    // Function to create overlay
    const createOverlay = () => {
        const overlay = document.createElement('div');
        overlay.classList.add('sidebar-overlay');
        document.body.appendChild(overlay);
    };

    // Function to remove overlay
    const removeOverlay = () => {
        const overlay = select('.sidebar-overlay');
        if (overlay) {
            overlay.remove();
        }
    };

    // Sidebar toggle (button in the date-time div)
    document.addEventListener('click', function(e) {
        if (e.target.matches('.toggle-sidebar-btn')) {
            select('body').classList.toggle('toggle-sidebar');
            if (select('body').classList.contains('toggle-sidebar')) {
                createOverlay(); // Show overlay when sidebar is open
            } else {
                removeOverlay(); // Remove overlay when sidebar is closed
            }
        }
    });

    // Sidebar close button (inside sidebar)
    document.addEventListener('click', function(e) {
        if (e.target.matches('.toggle-sidebar-close')) {
            select('body').classList.remove('toggle-sidebar');
            removeOverlay(); // Remove overlay when sidebar is closed
        }
    });

    // Auto-close the sidebar when the window is resized to 1200px or larger
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1200) {
            select('body').classList.remove('toggle-sidebar'); // Remove toggle class for larger screens
            removeOverlay(); // Remove overlay on larger screens
        }
    });

    // Prevent clicks on the overlay from closing the sidebar or interacting with underlying content
    document.addEventListener('click', function(e) {
        if (e.target.matches('.sidebar-overlay')) {
            e.stopPropagation(); // Prevent any click events on the overlay from propagating
        }
    });

})();
