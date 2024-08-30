<!-- resources/views/SSUHead/partials/pagination_links.blade.php -->

<div class="pagination">
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
</div>
