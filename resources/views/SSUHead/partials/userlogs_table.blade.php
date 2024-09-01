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
            <tr>
                <td>{{ $log->log_date }}</td>
                <td>{{ $log->vehicleOwner->driver_license_no }}</td>
                <td>{{ \Carbon\Carbon::parse($log->time_in)->format('g:i A') }}</td>
                <td>{{ \Carbon\Carbon::parse($log->time_out)->format('g:i A') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Showing X to Y of Z results -->
<div class="results-info">
    @php
        $start = ($userLogs->currentPage() - 1) * $userLogs->perPage() + 1;
        $end = min($start + $userLogs->perPage() - 1, $userLogs->total());
    @endphp
    <p>Showing {{ $start }} to {{ $end }} of {{ $userLogs->total() }} results</p>
</div>


<!-- {{-- Include the pagination --}} -->
@include('SSUHead.partials.userlogs_pagination')
