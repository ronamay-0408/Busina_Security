<!-- resources/views/SSUHead/partials/violation_table.blade.php -->

<table id="violationTable">
    <thead>
        <tr>
            <th>Date & Time</th>
            <th>Plate No</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        @foreach($violations as $violation)
            <tr>
                <td>{{ $violation->created_at->format('Y-m-d, g:i A') }}</td>
                <td>{{ $violation->plate_no }}</td>
                <td>
                    @if($violation->proof_image)
                        <button 
                            class="view-btn" 
                            data-image="{{ asset('storage/' . $violation->proof_image) }}" 
                            data-date="{{ $violation->created_at->format('F j, Y, g:i a') }}" 
                            data-plate="{{ $violation->plate_no }}" 
                            data-violation="{{ $violation->violationType->violation_name }}" 
                            data-location="{{ $violation->location }}" 
                            data-reported="{{ $violation->reportedBy->fullName }}" 
                            data-remarks="{{ $violation->remarks }}"
                        >View</button>
                    @else
                        No Image
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Showing X to Y of Z results -->
<div class="results-info">
    @php
        $start = ($violations->currentPage() - 1) * $violations->perPage() + 1;
        $end = min($start + $violations->perPage() - 1, $violations->total());
    @endphp
    <p>Showing {{ $start }} to {{ $end }} of {{$violations->total() }} results</p>
</div>


<!-- Include pagination links -->
<div class="pagination" >
    @include('SSUHead.partials.pagination', ['violations' => $violations])
</div>

    
