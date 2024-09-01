<!-- // resources/views/SSUHead/partials/userlogs_pagination.blade.php -->

<div id="userlogsPagination" class="pagination">
    {{-- Previous Page Link --}}
    @if ($userLogs->onFirstPage())
        <span class="page-item disabled">« Previous</span>
    @else
        <a class="page-item" href="{{ $userLogs->previousPageUrl() }}&per_page={{ request('per_page', 10) }}">« Previous</a>
    @endif

    {{-- Pagination Links --}}
    @foreach ($userLogs->getUrlRange(1, $userLogs->lastPage()) as $page => $url)
        @if ($page == $userLogs->currentPage())
            <span class="page-item active">{{ $page }}</span>
        @else
            <a class="page-item" href="{{ $url }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($userLogs->hasMorePages())
        <a class="page-item" href="{{ $userLogs->nextPageUrl() }}&per_page={{ request('per_page', 10) }}">Next »</a>
    @else
        <span class="page-item disabled">Next »</span>
    @endif
</div>
