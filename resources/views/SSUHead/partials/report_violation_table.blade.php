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
    document.querySelectorAll('#violationTable tr').forEach(function(row) {
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
<div class="pagination no-print">
    @include('SSUHead.partials.pagination', ['violations' => $violations])
</div>

    
