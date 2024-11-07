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
                    <button type="button" id="exportExcelButton" class="buttons" onclick="exportFiltered()">Export as Filtered Excel</button>
                    <button type="button" id="exportAllButton" class="buttons" onclick="exportAll()">Export All as Excel</button>
                </div>
            </div>

            <div class="search-filter">
                <div class="search-bar">
                    <input type="text" id="searchInput" name="search" placeholder="Search by plate no or violation type">
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
                     <select id="remarksFilter" class="filter-select">
                        <option value="">Remarks</option>
                        <option value="1">Not been settled</option>
                        <option value="2">Settled</option>
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
                    @include('SSUHead.partials.report_violation_table', ['violations' => $violations])
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    @include('SSUHead.partials.footer')

    <!-- JAVASCRIPT FOR AUTOMATIC SEARCH AND FILTERING -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const $tableContainer = $('#tableContainer');
            const $paginationLinks = $('#paginationLinks');
            const $filters = $('#searchInput, #yearFilter, #monthFilter, #dayFilter, #remarksFilter, #per_page');

            function fetchData(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $tableContainer.html(response.tableHtml);
                        $paginationLinks.html(response.paginationHtml);
                    }
                });
            }

            function buildUrl() {
                // Get the remarks filter value
                const remarks = $('#remarksFilter').val();

                // Map remarks numeric values to text or send as is (1 or 2)
                const remarksParam = (remarks === "1") ? "Not been settled" : (remarks === "2") ? "Settled" : "";

                const params = {
                    search: $('#searchInput').val(),
                    year: $('#yearFilter').val(),
                    month: $('#monthFilter').val(),
                    day: $('#dayFilter').val(),
                    remarks: remarksParam,  // Pass the mapped value for remarks
                    per_page: $('#per_page').val(),
                };

                const url = new URL(window.location.href);
                Object.keys(params).forEach(key => url.searchParams.set(key, params[key] || ''));
                return url.toString();
            }

            // Fetch data initially and on filter change
            fetchData(buildUrl());
            $filters.on('input change', () => fetchData(buildUrl()));

            // Handle pagination
            $(document).on('click', '#paginationLinks a', function(e) {
                e.preventDefault();
                fetchData($(this).attr('href'));
            });
        });
    </script>


    <script>
        function exportFiltered() {
            console.log('Export filtered button clicked');

            // Get the current filter values
            const search = document.getElementById('searchInput').value;
            const year = document.getElementById('yearFilter').value;
            const month = document.getElementById('monthFilter').value;
            const day = document.getElementById('dayFilter').value;
            const remarks = document.getElementById('remarksFilter').value;

            // Create a form dynamically to submit the filters
            const form = document.createElement('form');
            form.method = 'GET'; // Use GET for passing parameters
            form.action = "{{ route('export.filtered.excel') }}"; // Ensure this route is correct

            // Add filter inputs to the form as hidden inputs
            if (search) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'search';
                input.value = search;
                form.appendChild(input);
            }
            if (year) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'year';
                input.value = year;
                form.appendChild(input);
            }
            if (month) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'month';
                input.value = month;
                form.appendChild(input);
            }
            if (day) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'day';
                input.value = day;
                form.appendChild(input);
            }
            if (remarks) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remarks';
                input.value = remarks; // Remarks now passes 1 or 2
                form.appendChild(input);
            }

            // Log the form data to check correctness
            console.log('Form data:', { search, year, month, day, remarks });

            // Append form to body and submit it
            document.body.appendChild(form);
            form.submit();
        }
        
        function exportAll() {
            // Redirect to the URL that triggers the export for all records
            window.location.href = '/violations/export-all'; // Update with the correct route if needed
        }

    </script>


    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
