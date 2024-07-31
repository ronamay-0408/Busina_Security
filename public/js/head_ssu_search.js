document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInputSSU');
    const table = document.getElementById('ssuTable');
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