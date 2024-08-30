@php
    $start = ($unauthorizedRecords->currentPage() - 1) * $unauthorizedRecords->perPage() + 1;
    $end = min($start + $unauthorizedRecords->perPage() - 1, $unauthorizedRecords->total());
@endphp
<p>Showing {{ $start }} to {{ $end }} of {{ $unauthorizedRecords->total() }} results</p>
