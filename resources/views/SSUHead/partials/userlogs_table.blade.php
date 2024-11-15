<!-- // resources/views/SSUHead/partials/userlogs_table.blade.php -->

<table id="userlogsTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Drivers License</th>
            <th>Time In</th>
            <th>Time Out</th>
        </tr>
    </thead>
    <tbody>
        @foreach($userLogs as $log)
            <tr data-url="{{ route('SubUserLogs', ['vehicleOwnerId' => $log->vehicleOwner->id]) }}" style="cursor: pointer;">
                <td>{{ \Carbon\Carbon::parse($log->log_date)->format('Y-m-d') }}</td>  <!-- Parse log_date -->
                <td>{{ $log->vehicleOwner->driver_license_no }}</td>
                <td>{{ \Carbon\Carbon::parse($log->time_in)->format('g:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($log->time_out)->format('g:i A') }}</td>
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
<div id="userlogsPagination" class="pagination no-print">
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
</div>

