// Modal functionality
var modal = document.getElementById("myModal");
var img = document.getElementById("modal-image");
var btns = document.querySelectorAll(".view-btn");
var span = document.getElementsByClassName("close")[0];

btns.forEach(function(btn) {
    btn.onclick = function() {
        img.src = this.getAttribute("data-image");
        modal.style.display = "block";
    }
});

span.onclick = function() {
    modal.style.display = "none";
}

window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// SEARCH JS //
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('violationTable');
    const rows = table.querySelectorAll('tbody tr');

    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.toLowerCase();

        rows.forEach(row => {
            const cells = row.getElementsByTagName('td');
            let match = false;

            for (let i = 0; i < cells.length; i++) {
                const cell = cells[i].textContent.toLowerCase();
                if (cell.includes(query)) {
                    match = true;
                    break;
                }
            }

            if (match) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});