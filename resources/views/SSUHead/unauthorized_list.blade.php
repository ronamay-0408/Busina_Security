<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - Unauthorized Vehicle</title>
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
        <div class="date-time">
        </div>

        <div class="submain">
            <div class="main-title">
                <h3 class="per-title">VEHICLE OWNER REPORT</h3>
                <div class="submain-btn">
                    <button type="button" id="exportExcelButton" class="buttons" onclick="exportFiltered()">Export Filtered as Excel</button>
                    <button type="button" id="exportAllExcelButton" class="buttons" onclick="exportAll()">Export All Details as Excel</button>
                </div>
            </div>


            <div class="search-filter">
                <div class="search-bar">
                    <input type="text" id="searchInputUnauthorized" placeholder="Search by plate number">
                </div>
                <!-- Filter Fields -->
                <div class="filter-field">
                    <!-- Year Filter -->
                    <select id="yearFilter" class="filter-select" onchange="submitFilters()">
                        <option value="">Select Year</option>
                        @foreach(range(date('Y'), 2000) as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Month Filter -->
                    <select id="monthFilter" class="filter-select" onchange="submitFilters()">
                        <option value="">Select Month</option>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}" {{ request('month') == str_pad($month, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Day Filter -->
                    <select id="dayFilter" class="filter-select" onchange="submitFilters()">
                        <option value="">Select Day</option>
                        @foreach(range(1, 31) as $day)
                            <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}" {{ request('day') == str_pad($day, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="head_view_unauthorized_table">
                <!-- Dropdown to select number of rows per page -->
                <form method="GET" id="per-page-form" action="{{ url()->current() }}" class="per-page-form">
                    <label for="per_page">Show:</label>
                    <select name="per_page" id="per_page" onchange="submitFilters()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                        <option value="250" {{ request('per_page', 10) == 250 ? 'selected' : '' }}>250</option>
                        <option value="500" {{ request('per_page', 10) == 500 ? 'selected' : '' }}>500</option>
                        <option value="1000" {{ request('per_page', 10) == 1000 ? 'selected' : '' }}>1000</option>
                    </select>
                </form>

                <div id="unauthorized-data">
                    @include('SSUHead.partials.unauthorized_table', ['unauthorizedRecords' => $unauthorizedRecords])
                </div>
            </div>
        </div>
    </main>

    @include('SSUHead.partials.footer')

    <!-- JAVASCRIPT FOR AJAX -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInputUnauthorized');
            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const dayFilter = document.getElementById('dayFilter');
            const perPageForm = document.getElementById('per_page');

            function submitFilters(page = 1) {
                const searchText = searchInput.value.trim();
                const selectedYear = yearFilter.value;
                const selectedMonth = monthFilter.value;
                const selectedDay = dayFilter.value;
                const perPage = perPageForm.value;

                const queryParams = new URLSearchParams({
                    search: searchText,
                    year: selectedYear,
                    month: selectedMonth,
                    day: selectedDay,
                    per_page: perPage,
                    page: page
                }).toString();

                // Update URL without reloading
                history.pushState(null, '', `?${queryParams}`);

                fetch(`{{ route('unauthorized_list') }}?${queryParams}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('unauthorized-data').innerHTML = html;
                    updatePaginationLinks();
                })
                .catch(error => console.error('Error:', error));
            }

            function updatePaginationLinks() {
                document.querySelectorAll('.pagination a.page-item').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault();
                        const page = new URL(this.href).searchParams.get('page');
                        if (page) {
                            submitFilters(page);
                        }
                    });
                });
            }

            searchInput.addEventListener('input', debounce(() => submitFilters(), 300));
            yearFilter.addEventListener('change', () => submitFilters());
            monthFilter.addEventListener('change', () => submitFilters());
            dayFilter.addEventListener('change', () => submitFilters());
            perPageForm.addEventListener('change', () => submitFilters());

            // Initialize with current URL parameters
            const params = new URLSearchParams(window.location.search);
            searchInput.value = params.get('search') || '';
            yearFilter.value = params.get('year') || '';
            monthFilter.value = params.get('month') || '';
            dayFilter.value = params.get('day') || '';
            perPageForm.value = params.get('per_page') || '10';

            updatePaginationLinks();
        });

        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this, args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }
    </script>

    <script>
        function exportFiltered() {
            console.log('Export filtered button clicked'); // Debugging line

            // Get the current filter values
            const search = document.getElementById('searchInputUnauthorized').value; // Update ID
            const year = document.getElementById('yearFilter').value; // Update ID
            const month = document.getElementById('monthFilter').value; // Update ID
            const day = document.getElementById('dayFilter').value; // Update ID
            
            // Create a form dynamically to submit the filters
            const form = document.createElement('form');
            form.method = 'GET'; // Change to 'POST' if necessary
            form.action = "{{ route('exportUnauthorizedExcel') }}"; // Updated route for filtered export
            
            // Add filter inputs to the form
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
            
            // Log the form data to check correctness
            console.log('Form data:', {
                search,
                year,
                month,
                day
            });
            
            // Append form to body and submit
            document.body.appendChild(form);
            form.submit();
        }
        function exportAll() {
            // Redirect to the export route for all records
            window.location.href = "{{ route('exportAllUnauthorizedExcel') }}"; // Updated route for all records
        }
    </script>


    <!-- Search Js -->
    <script src="{{ asset('js/head_unauthorized_search.js') }}"></script>

    <!-- Filtering and EXPORT JS File -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('js/head_unauthorized_filtering.js') }}"></script>

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
