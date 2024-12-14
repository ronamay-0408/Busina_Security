<!-- resources/views/SSUHead/partials/violation_table.blade.php -->

<table id="violationTable">
    <thead>
        <tr>
            <th>Report At</th>
            <th>Plate No</th>
            <th>Violation Type</th>
            <th>Remarks</th>
        </tr>
    </thead>
    <tbody>
        @foreach($violations as $violation)
            <tr data-url="{{ route('subViolationList', ['id' => $violation->id]) }}" style="cursor: pointer;">
                <td>{{ $violation->created_at->format('Y-m-d, g:i A') }}</td>
                <td>{{ $violation->plate_no }}</td>
                <td>{{ $violation->violationType->violation_name }}</td>
                <td>
                    <span class="{{ $violation->remarks == 'Settled' ? 'settled' : ($violation->remarks == 'Not been settled' ? 'not-settled' : '') }}">
                        {{ $violation->remarks }}
                    </span>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    document.querySelectorAll('#violationTable tbody tr').forEach(function(row) {
        row.addEventListener('click', function() {
            var url = this.getAttribute('data-url');
            window.location.href = url;
        });
    });
</script>

<!-- Showing X to Y of Z results -->
<div class="results-info no-print">
    @php
        $start = ($violations->currentPage() - 1) * $violations->perPage() + 1;
        $end = min($start + $violations->perPage() - 1, $violations->total());
    @endphp
    <p>Showing {{ $start }} to {{ $end }} of {{$violations->total() }} results</p>
</div>

<!-- Include pagination links -->
<!-- <div class="pagination no-print" id="paginationLinks">
    @if ($violations->onFirstPage())
        <span class="page-item disabled">« Previous</span>
    @else
        <a class="page-item" href="{{ $violations->previousPageUrl() }}&search={{ request('search') }}&remarks={{ request('remarks') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page') }}">« Previous</a>
    @endif

    @foreach ($violations->getUrlRange(1, $violations->lastPage()) as $page => $url)
        @if ($page == $violations->currentPage())
            <span class="page-item active">{{ $page }}</span>
        @else
            <a class="page-item" href="{{ $url }}&search={{ request('search') }}&remarks={{ request('remarks') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page') }}">{{ $page }}</a>
        @endif
    @endforeach

    @if ($violations->hasMorePages())
        <a class="page-item" href="{{ $violations->nextPageUrl() }}&search={{ request('search') }}&remarks={{ request('remarks') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page') }}">Next »</a>
    @else
        <span class="page-item disabled">Next »</span>
    @endif
</div> -->

<!-- Include pagination links -->
<div id="violationsPagination" class="pagination no-print">
    {{-- Previous Page Link --}}
    @if ($violations->onFirstPage())
        <span class="page-item disabled">« Previous</span>
    @else
        <a class="page-item" href="{{ $violations->previousPageUrl() }}&search={{ request('search') }}&remarks={{ request('remarks') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page', 10) }}">« Previous</a>
    @endif

    {{-- Pagination Links --}}
    @php
        $currentPage = $violations->currentPage();
        $lastPage = $violations->lastPage();

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
            <a class="page-item" href="{{ $violations->url($page) }}&search={{ request('search') }}&remarks={{ request('remarks') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a>
        @endif
    @endfor

    {{-- Next Page Link --}}
    @if ($violations->hasMorePages())
        <a class="page-item" href="{{ $violations->nextPageUrl() }}&search={{ request('search') }}&remarks={{ request('remarks') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page', 10) }}">Next »</a>
    @else
        <span class="page-item disabled">Next »</span>
    @endif
</div>