<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - View Reports</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ssu_head.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    @include('MainPartials.ssu_sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i> <!-- Moved toggle button here --></div>
            <div class="date-time"></div>
        </div>
        
        <div class="submain">
            <div class="main-title">
                <h3 class="per-title">MY REPORTS</h3>
            </div>

            <div class="search-bar">
                <form action="{{ route('violations.index') }}" method="GET">
                    <input type="text" placeholder="Search by Plate Number" name="search" value="{{ request('search') }}">
                    <button type="submit">Search</button>
                </form>
            </div>

            <!-- resources/views/view_reports.blade.php -->
            <div class="myreports">
                @forelse($violations as $violation)
                    <div class="filed-child">
                        <div class="myfiled">
                            <h3><a href="{{ route('violation.show', $violation->id) }}">{{ $violation->plate_no }}</a></h3>
                            <p>{{ $violation->violationType->violation_name }}</p>
                            <div class="date">
                                <p>{{ $violation->created_at->format('m-d-Y') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="no-result">No results found.</p>
                @endforelse
            </div>

            <!-- Showing X to Y of Z results -->
            <div class="results-info">
                @php
                    $start = ($violations->currentPage() - 1) * $violations->perPage() + 1;
                    $end = min($start + $violations->perPage() - 1, $violations->total());
                @endphp
                <p>Showing {{ $start }} to {{ $end }} of {{ $violations->total() }} results</p>
            </div>

            <!-- Custom Pagination -->
            <div class="pagination">
                {{-- Previous Page Link --}}
                @if ($violations->onFirstPage())
                    <span class="page-item disabled">« Previous</span>
                @else
                    <a class="page-item" href="{{ $violations->previousPageUrl() }}&per_page={{ request('per_page', 10) }}">« Previous</a>
                @endif

                {{-- Pagination Links --}}
                @foreach ($violations->getUrlRange(1, $violations->lastPage()) as $page => $url)
                    @if ($page == $violations->currentPage())
                        <span class="page-item active">{{ $page }}</span>
                    @else
                        <a class="page-item" href="{{ $url }}&per_page={{ request('per_page', 10) }}">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($violations->hasMorePages())
                    <a class="page-item" href="{{ $violations->nextPageUrl() }}&per_page={{ request('per_page', 10) }}">Next »</a>
                @else
                    <span class="page-item disabled">Next »</span>
                @endif
            </div>
        </div>
    </main><!-- End #main -->


    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
