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

    // Sidebar toggle (button in the date-time div)
    document.addEventListener('click', function(e) {
        if (e.target.matches('.toggle-sidebar-btn')) {
            select('body').classList.toggle('toggle-sidebar');
        }
    });

    // Sidebar close button (inside sidebar)
    document.addEventListener('click', function(e) {
        if (e.target.matches('.toggle-sidebar-close')) {
            select('body').classList.remove('toggle-sidebar');
        }
    });

    // Auto-close the sidebar when the window is resized to 1200px or larger
    window.addEventListener('resize', function() {
        if (window.innerWidth >= 1200) {
            select('body').classList.remove('toggle-sidebar'); // Remove toggle class for larger screens
        }
    });

})();
