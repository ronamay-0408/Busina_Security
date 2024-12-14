<!-- resources/views/SSUHead/partials/pagination_links.blade.php -->

<!-- <div class="pagination">
    {{-- Previous Page Link --}}
    @if ($unauthorizedRecords->onFirstPage())
        <span class="page-item disabled">« Previous</span>
    @else
        <a class="page-item" href="{{ $unauthorizedRecords->previousPageUrl() }}&per_page={{ request('per_page', 10) }}">« Previous</a>
    @endif

    {{-- Pagination Links --}}
    @foreach ($unauthorizedRecords->getUrlRange(1, $unauthorizedRecords->lastPage()) as $page => $url)
        @if ($page == $unauthorizedRecords->currentPage())
            <span class="page-item active">{{ $page }}</span>
        @else
            <a class="page-item" href="{{ $url }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($unauthorizedRecords->hasMorePages())
        <a class="page-item" href="{{ $unauthorizedRecords->nextPageUrl() }}&per_page={{ request('per_page', 10) }}">Next »</a>
    @else
        <span class="page-item disabled">Next »</span>
    @endif
</div> -->

<!-- Include pagination links -->
<div class="pagination">
    {{-- Previous Page Link --}}
    @if ($unauthorizedRecords->onFirstPage())
        <span class="page-item disabled">« Previous</span>
    @else
        <a class="page-item" href="{{ $unauthorizedRecords->previousPageUrl() }}&per_page={{ request('per_page', 10) }}">« Previous</a>
    @endif

    {{-- Pagination Links --}}
    @php
        $currentPage = $unauthorizedRecords->currentPage();
        $lastPage = $unauthorizedRecords->lastPage();

        // Show up to 5 pages by default
        $pageWindow = 5;

        // Calculate the starting and ending page numbers to create a window of pages
        if ($currentPage <= 3) {
            // Show the first 5 pages if we are at the beginning
            $startPage = 1;
            $endPage = min($pageWindow, $lastPage);
        } else if ($currentPage + 2 >= $lastPage) {
            // Show the last 5 pages if we are near the end
            $startPage = max($lastPage - $pageWindow + 1, 1);
            $endPage = $lastPage;
        } else {
            // Show a centered range around the current page
            $startPage = $currentPage - 2;
            $endPage = $currentPage + 2;
        }
    @endphp

    @for ($page = $startPage; $page <= $endPage; $page++)
        @if ($page == $currentPage)
            <span class="page-item active">{{ $page }}</span>
        @else
            <a class="page-item" href="{{ $unauthorizedRecords->url($page) }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a>
        @endif
    @endfor

    {{-- Next Page Link --}}
    @if ($unauthorizedRecords->hasMorePages())
        <a class="page-item" href="{{ $unauthorizedRecords->nextPageUrl() }}&per_page={{ request('per_page', 10) }}">Next »</a>
    @else
        <span class="page-item disabled">Next »</span>
    @endif
</div>