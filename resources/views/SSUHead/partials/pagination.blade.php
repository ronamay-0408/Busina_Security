@if ($violations->onFirstPage())
    <span class="page-item disabled">« Previous</span>
@else
    <a class="page-item" href="{{ $violations->previousPageUrl() }}">« Previous</a>
@endif

@foreach ($violations->getUrlRange(1, $violations->lastPage()) as $page => $url)
    @if ($page == $violations->currentPage())
        <span class="page-item active">{{ $page }}</span>
    @else
        <a class="page-item" href="{{ $url }}">{{ $page }}</a>
    @endif
@endforeach

@if ($violations->hasMorePages())
    <a class="page-item" href="{{ $violations->nextPageUrl() }}">Next »</a>
@else
    <span class="page-item disabled">Next »</span>
@endif
