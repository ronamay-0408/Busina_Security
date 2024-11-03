<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - Violations</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ssu_head.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
</head>
    <style>
        @media (max-width: 600px) {
            .filter-field {
                flex-wrap: wrap;
            }
            th {
                font-size: 14px;
            }
            td{
                font-size: 12px;
            }
        }
    </style>
<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="bar">
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        
        <div class="logo">
            <img src="{{ asset('images/BUsina logo (1) 2.png') }}" alt="">
        </div>
    </header><!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <aside id="sidebar" class="sidebar">

        <div class="profile">
            <div class="image">
                <img src="{{ asset('images/BUsina logo (1) 1.png') }}" alt="">
            </div>
            <div class="head_info">
                @if(Session::has('user'))
                    <h2>{{ Session::get('user')['fname'] }} {{ Session::get('user')['lname'] }}</h2>
                    <h3>{{ Session::get('user')['email'] }}</h3>
                @endif
            </div>
        </div>

        @include('SSUHead.partials.sidebar')
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="date-time"></div>

        <div class="submain">
            <div class="main-title">
                <h3 class="per-title">REPORTED VIOLATIONS</h3>
                <div class="submain-btn">
                    <button type="submit" id="exportCsvButton" class="buttons">Export as CSV</button>
                    <button type="button" id="exportAllButton" class="buttons">Export All Details to CSV</button>
                </div>
            </div>

            <div class="search-filter">
                <div class="search-bar">
                    <input type="text" id="searchInput" name="search" placeholder="Search by plate number">
                </div>

                <div class="filter-field">
                    <!-- Year Filter -->
                    <select id="yearFilter" name="year" class="filter-select" onchange="this.form.submit()">
                        <option value="">Select Year</option>
                        @foreach(range(date('Y'), 2000) as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>

                    <!-- Month Filter -->
                    <select id="monthFilter" name="month" class="filter-select" onchange="this.form.submit()">
                        <option value="">Select Month</option>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ request('month') == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Day Filter -->
                    <select id="dayFilter" name="day" class="filter-select" onchange="this.form.submit()">
                        <option value="">Select Day</option>
                        @foreach(range(1, 31) as $day)
                            <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}" {{ request('day') == str_pad($day, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $day }}</option>
                        @endforeach
                    </select>

                     <!-- Remarks Filter -->
                    <select id="remarksFilter" name="remarks" class="filter-select" onchange="this.form.submit()">
                        <option value="">Select Remarks</option>
                        <option value="Settled" {{ request('remarks') == 'Settled' ? 'selected' : '' }}>Settled</option>
                        <option value="Not been settled" {{ request('remarks') == 'Not been settled' ? 'selected' : '' }}>Not been settled</option>
                    </select>
                </div>
            </div>
            
            <div class="head_view_violation_table">
                <!-- Dropdown to select number of rows per page -->
                <form method="GET" action="{{ url()->current() }}" class="per-page-form">
                    <label for="per_page">Show:</label>
                    <select name="per_page" id="per_page" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                        <option value="250" {{ request('per_page', 10) == 250 ? 'selected' : '' }}>250</option>
                        <option value="500" {{ request('per_page', 10) == 500 ? 'selected' : '' }}>500</option>
                        <option value="1000" {{ request('per_page', 10) == 1000 ? 'selected' : '' }}>1000</option>
                    </select>
                </form>

                <!-- Table -->
                <div id="tableContainer">
                    @include('SSUHead.partials.violation_table', ['violations' => $violations])
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    @include('SSUHead.partials.footer')

    <!-- JAVASCRIPT FOR AUTOMATIC SEARCH AND FILTERING -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function buildUrl(page = 1) {
                const params = {
                    search: $('#searchInput').val().trim(),
                    year: $('#yearFilter').val(),
                    month: $('#monthFilter').val(),
                    day: $('#dayFilter').val(),
                    remarks: $('#remarksFilter').val(),
                    per_page: $('#per_page').val(),
                    page: page
                };
                return new URLSearchParams(params).toString();
            }

            function fetchData(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $('#tableContainer').html(response.tableHtml);
                        $('#paginationLinks').html(response.paginationHtml);
                    },
                    error: function(xhr) {
                        console.error('Error fetching data:', xhr);
                    }
                });
            }

            function handlePagination(page) {
                const queryParams = buildUrl(page);
                fetchData(`{{ route('violation_list') }}?${queryParams}`);
            }

            // Handle search and filter events
            $('#searchInput, #yearFilter, #monthFilter, #dayFilter, #remarksFilter, #per_page').on('input change', function() {
                fetchData(`{{ route('violation_list') }}?${buildUrl()}`);
            });

            // Handle pagination link clicks
            $(document).on('click', '#paginationLinks a', function(e) {
                e.preventDefault();
                const page = new URL(this.href).searchParams.get('page');
                if (page) handlePagination(page);
            });

            // Handle CSV exports
            function handleExport(url) {
                const queryParams = buildUrl();
                window.location.href = `${url}?${queryParams}`;
            }

            // Handle the export CSV button click
            $('#exportCsvButton').on('click', function() {
                const currentPage = new URLSearchParams(window.location.search).get('page') || 1;
                const queryParams = buildUrl(currentPage);
                const exportUrl = `{{ route('exportViolationCsv') }}?${queryParams}`;
                window.location.href = exportUrl;
            });

            // Handle the export All button click
            $('#exportAllButton').on('click', function() {
                const queryParams = buildUrl();
                const exportUrl = `{{ route('exportAllViolationCsv') }}?${queryParams}`;
                window.location.href = exportUrl;
            });

            // Initial fetch on page load
            fetchData(`{{ route('violation_list') }}?${buildUrl()}`);
        });
    </script>

    <!-- Include other necessary JavaScript files -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <script src="{{ asset('js/date_time.js') }}"></script>

</body>
</html>
