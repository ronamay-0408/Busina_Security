<!-- resources/views/MainPartials/myviolation.blade.php -->

<div class="myreports">
    @foreach($violations as $violation)
        <div class="filed-child">
            <div class="myfiled">
                <h3><a href="{{ route('violation.show', $violation->id) }}">{{ $violation->plate_no }}</a></h3>
                <p>{{ $violation->violationType->violation_name }}</p>
                <div class="date">
                    <p>{{ $violation->created_at->format('m-d-Y') }}</p>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Show this message if no violations are found -->
    @if ($violations->isEmpty())
        <p class="no-result">No results found.</p>
    @endif
</div>

<!-- Results Information -->
<div class="results-info">
    @php
        $start = ($violations->currentPage() - 1) * $violations->perPage() + 1;
        $end = min($start + $violations->perPage() - 1, $violations->total());
    @endphp
    <p>Showing {{ $start }} to {{ $end }} of {{ $violations->total() }} results</p>
</div>

<!-- Pagination -->
<div class="pagination">
    {{-- Previous Page Link --}}
    @if ($violations->onFirstPage())
        <span class="page-item disabled">« Previous</span>
    @else
        <a class="page-item" href="{{ $violations->previousPageUrl() }}&search={{ request('search') }}">« Previous</a>
    @endif

    {{-- Pagination Links --}}
    @foreach ($violations->getUrlRange(1, $violations->lastPage()) as $page => $url)
        @if ($page == $violations->currentPage())
            <span class="page-item active">{{ $page }}</span>
        @else
            <a class="page-item" href="{{ $url }}&search={{ request('search') }}">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($violations->hasMorePages())
        <a class="page-item" href="{{ $violations->nextPageUrl() }}&search={{ request('search') }}">Next »</a>
    @else
        <span class="page-item disabled">Next »</span>
    @endif
</div>

