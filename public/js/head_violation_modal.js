// Modal functionality
var modal = document.getElementById("myModal");
var img = document.getElementById("modal-image");
var dateElem = document.getElementById("modal-date");
var plateElem = document.getElementById("modal-plate");
var violationElem = document.getElementById("modal-violation");
var locationElem = document.getElementById("modal-location");
var reportedElem = document.getElementById("modal-reported");
var remarksElem = document.getElementById("modal-remarks");
var btns = document.querySelectorAll(".view-btn");
var span = document.getElementsByClassName("close")[0];

btns.forEach(function(btn) {
    btn.onclick = function() {
        // Update modal content with data attributes
        img.src = this.getAttribute("data-image");
        dateElem.textContent = this.getAttribute("data-date");
        plateElem.textContent = this.getAttribute("data-plate");
        violationElem.textContent = this.getAttribute("data-violation");
        locationElem.textContent = this.getAttribute("data-location");
        reportedElem.textContent = this.getAttribute("data-reported");
        remarksElem.textContent = this.getAttribute("data-remarks");

        // Show the modal
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