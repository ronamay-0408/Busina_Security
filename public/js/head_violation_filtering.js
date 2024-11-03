$(document).ready(function() {
    function fetchData(url) {
        $.ajax({
            url: url,
            method: 'GET',
            success: function(response) {
                $('#tableContainer').html(response.tableHtml);
                $('#paginationLinks').html(response.paginationHtml);
            }
        });
    }

    function buildUrl() {
        const search = $('#searchInput').val();
        const year = $('#yearFilter').val();
        const month = $('#monthFilter').val();
        const day = $('#dayFilter').val();
        const remarks = $('#remarksFilter').val(); // Get remarks filter value
        const perPage = $('#per_page').val();
        const url = new URL(window.location.href);

        url.searchParams.set('search', search);
        url.searchParams.set('year', year);
        url.searchParams.set('month', month);
        url.searchParams.set('day', day);
        url.searchParams.set('remarks', remarks); // Set remarks filter
        url.searchParams.set('per_page', perPage);

        return url.toString();
    }

    // Initial fetch
    fetchData(buildUrl());

    // Event listeners for search and filters
    $('#searchInput, #yearFilter, #monthFilter, #dayFilter, #remarksFilter').on('input change', function() {
        fetchData(buildUrl());
    });

    $('#per_page').on('change', function() {
        fetchData(buildUrl());
    });

    // Handle pagination clicks
    $(document).on('click', '#paginationLinks a', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        fetchData(url);
    });
});