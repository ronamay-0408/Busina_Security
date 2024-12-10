<!-- // resources/views/SSUHead/partials/userlogs_table.blade.php -->

<table id="userlogsTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Registered Full Name</th>
            <th>Time In</th>
            <th>Time Out</th>
        </tr>
    </thead>
    <tbody>
        @foreach($userLogs as $log)
            <tr data-url="{{ route('SubUserLogs', ['vehicleOwnerId' => $log->vehicleOwner->id]) }}" style="cursor: pointer;">
                <td>{{ \Carbon\Carbon::parse($log->log_date)->format('Y-m-d') }}</td>  <!-- Parse log_date -->
                <td>{{ $log->vehicleOwner->fname }} {{ $log->vehicleOwner->mname }} {{ $log->vehicleOwner->lname }}</td>
                <td>{{ \Carbon\Carbon::parse($log->time_in)->format('g:i A') }}</td>
                <td>@if($log->time_out)
                        {{ \Carbon\Carbon::parse($log->time_out)->format('g:i A') }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script>
    document.querySelectorAll('#userlogsTable tr').forEach(function(row) {
        row.addEventListener('click', function() {
            var url = this.getAttribute('data-url');
            window.location.href = url;
        });
    });
</script>

<!-- Showing X to Y of Z results -->
<div class="results-info no-print">
    @php
        $start = ($userLogs->currentPage() - 1) * $userLogs->perPage() + 1;
        $end = min($start + $userLogs->perPage() - 1, $userLogs->total());
    @endphp
    <p>Showing {{ $start }} to {{ $end }} of {{ $userLogs->total() }} results</p>
</div>

<!-- Include pagination links -->
<!-- <div id="userlogsPagination" class="pagination no-print">
    {{-- Previous Page Link --}}
    @if ($userLogs->onFirstPage())
        <span class="page-item disabled">« Previous</span>
    @else
        <a class="page-item" href="{{ $userLogs->previousPageUrl() }}&search={{ request('search') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page', 10) }}">« Previous</a>
    @endif

    {{-- Pagination Links --}}
    @foreach ($userLogs->getUrlRange(1, $userLogs->lastPage()) as $page => $url)
        @if ($page == $userLogs->currentPage())
            <span class="page-item active">{{ $page }}</span>
        @else
            <a class="page-item" href="{{ $url }}&search={{ request('search') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($userLogs->hasMorePages())
        <a class="page-item" href="{{ $userLogs->nextPageUrl() }}&search={{ request('search') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page', 10) }}">Next »</a>
    @else
        <span class="page-item disabled">Next »</span>
    @endif
</div> -->

<!-- Include pagination links -->
<div id="userlogsPagination" class="pagination no-print">
    {{-- Previous Page Link --}}
    @if ($userLogs->onFirstPage())
        <span class="page-item disabled">« Previous</span>
    @else
        <a class="page-item" href="{{ $userLogs->previousPageUrl() }}&search={{ request('search') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page', 10) }}">« Previous</a>
    @endif

    {{-- Pagination Links --}}
    @php
        $currentPage = $userLogs->currentPage();
        $lastPage = $userLogs->lastPage();

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
            <a class="page-item" href="{{ $userLogs->url($page) }}&search={{ request('search') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a>
        @endif
    @endfor

    {{-- Next Page Link --}}
    @if ($userLogs->hasMorePages())
        <a class="page-item" href="{{ $userLogs->nextPageUrl() }}&search={{ request('search') }}&year={{ request('year') }}&month={{ request('month') }}&day={{ request('day') }}&per_page={{ request('per_page', 10) }}">Next »</a>
    @else
        <span class="page-item disabled">Next »</span>
    @endif
</div>


