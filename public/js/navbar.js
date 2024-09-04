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

    // Sidebar toggle
    document.addEventListener('click', function(e) {
        if (e.target.matches('.toggle-sidebar-btn')) {
            select('body').classList.toggle('toggle-sidebar');
        }
    });
})();
