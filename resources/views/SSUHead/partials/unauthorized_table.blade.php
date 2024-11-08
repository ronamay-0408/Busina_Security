<!-- // resources/views/SSUHead/partials/unauthorized_table.blade.php -->

<table id="unauthorizedTable">
    <thead>
        <tr>
            <th>Date</th>
            <th>Plate No</th>
            <th>Time In</th>
            <th>Time Out</th>
        </tr>
    </thead>
    <tbody>
        @foreach($unauthorizedRecords as $record)
            <tr>
                <td>{{ $record->log_date }}</td>
                <td>{{ $record->plate_no }}</td>
                <td>{{ \Carbon\Carbon::parse($record->time_in)->format('g:i A') }}</td>
                <td>
                    @if($record->time_out)
                        {{ \Carbon\Carbon::parse($record->time_out)->format('g:i A') }}
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Showing X to Y of Z results -->
<div class="results-info no-print">
    @php
        $start = ($unauthorizedRecords->currentPage() - 1) * $unauthorizedRecords->perPage() + 1;
        $end = min($start + $unauthorizedRecords->perPage() - 1, $unauthorizedRecords->total());
    @endphp
    <p>Showing {{ $start }} to {{ $end }} of {{ $unauthorizedRecords->total() }} results</p>
</div>


<!-- Include pagination links -->
<div class="pagination no-print">
    @include('SSUHead.partials.pagination_links', ['unauthorizedRecords' => $unauthorizedRecords])
</div>

