<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - Vehicle Owner Logs</title>
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
                    <button type="button" id="printUserLogs" class="buttons">Print</button>
                </div>
            </div>

            <div class="search-filter">
                <div class="search-bar">
                    <input type="text" id="searchInputUnauthorized" placeholder="Search by Drivers License">
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

                <div id="userlogs-data">
                    @include('SSUHead.partials.userlogs_table')
                </div>
            </div>
        </div>
    </main>

    @include('SSUHead.partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInputUnauthorized');
            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const dayFilter = document.getElementById('dayFilter');
            const perPageForm = document.getElementById('per-page-form');
            const perPageSelect = document.getElementById('per_page');

            // Load saved state from URL parameters
            const params = new URLSearchParams(window.location.search);

            searchInput.value = params.get('search') || '';
            yearFilter.value = params.get('year') || '';
            monthFilter.value = params.get('month') || '';
            dayFilter.value = params.get('day') || '';
            perPageSelect.value = params.get('per_page') || '10';

            // Function to update the URL with current filter values and reload the page
            const updateFilters = () => {
                const searchValue = encodeURIComponent(searchInput.value.trim());
                const yearValue = encodeURIComponent(yearFilter.value);
                const monthValue = encodeURIComponent(monthFilter.value);
                const dayValue = encodeURIComponent(dayFilter.value);
                const perPageValue = encodeURIComponent(perPageSelect.value);

                const url = new URL(window.location.href);
                url.searchParams.set('search', searchValue);
                url.searchParams.set('year', yearValue);
                url.searchParams.set('month', monthValue);
                url.searchParams.set('day', dayValue);
                url.searchParams.set('per_page', perPageValue);

                window.history.replaceState({}, '', url.toString()); // Update URL without reloading
            };

            const fetchUserLogs = async () => {
                const url = new URL(window.location.href);
                try {
                    const response = await fetch(url.toString(), {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    document.getElementById('userlogs-data').innerHTML = doc.querySelector('#userlogs-data').innerHTML;
                    document.getElementById('userlogsPagination').innerHTML = doc.querySelector('#userlogsPagination').innerHTML;
                    document.querySelector('.results-info').innerHTML = doc.querySelector('.results-info').innerHTML;
                } catch (error) {
                    console.error('Error fetching user logs:', error);
                }
            };

            // Event listeners for filters and search input
            searchInput.addEventListener('input', () => {
                updateFilters();
                fetchUserLogs();
            });
            yearFilter.addEventListener('change', () => {
                updateFilters();
                fetchUserLogs();
            });
            monthFilter.addEventListener('change', () => {
                updateFilters();
                fetchUserLogs();
            });
            dayFilter.addEventListener('change', () => {
                updateFilters();
                fetchUserLogs();
            });

            // Handle form submission for per-page changes
            perPageForm.addEventListener('submit', (event) => {
                event.preventDefault(); // Prevent default form submission
                updateFilters(); // Update URL with current filters
                fetchUserLogs(); // Fetch user logs with updated filters
            });

            perPageSelect.addEventListener('change', () => {
                perPageForm.submit(); // Submit the form to update per-page value
            });
        });
    </script>

    <script>
        function exportFiltered() {
            console.log('Export filtered button clicked');

            // Get the current filter values
            const search = document.getElementById('searchInputUnauthorized').value;
            const year = document.getElementById('yearFilter').value;
            const month = document.getElementById('monthFilter').value;
            const day = document.getElementById('dayFilter').value;

            // Create a form dynamically to submit the filters
            const form = document.createElement('form');
            form.method = 'GET'; // Adjust if necessary
            form.action = "{{ route('userlogs.export') }}"; // Ensure this route is correct

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
            console.log('Form data:', { search, year, month, day });

            // Append form to body and submit
            document.body.appendChild(form);
            form.submit();
        }

        function exportAll() {
            // Redirect to the URL that triggers the export for all records
            window.location.href = '/userlogs/export-all';
        }
    </script>

    <script>
        document.getElementById('printUserLogs').addEventListener('click', function() {
            printUserLogsTable();
        });

        function printUserLogsTable() {
            // Fetch the current user data (Blade data passed into JS)
            const userFname = '{{ Session::get("user")["fname"] ?? "Unknown" }}';
            const userLname = '{{ Session::get("user")["lname"] ?? "User" }}';

            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const formattedTime = currentDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

            // Get the content of the userlogs table
            const tableContent = document.getElementById('userlogsTable').outerHTML;

            // Open the print window
            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print User Logs</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                            }
                            @media print {
                                /* Hide default browser print header and footer */
                                @page {
                                    margin: 10px 20px 20px 20px;
                                }
                                    
                                /* Hide the default header (browser-specific) */
                                .no-print {
                                    display: none;
                                }
                                /* Header Styles */
                                .print-header {
                                    text-align: center;
                                    margin-bottom: 20px;
                                }
                                .print-header h1 {
                                    margin: 0;
                                    font-size: 20px;
                                    font-weight: bold;
                                }
                                .print-header h2 {
                                    margin: 0;
                                    font-size: 16px;
                                    font-weight: normal;
                                }
                                .print-header h3 {
                                    margin: 0;
                                    font-size: 16px;
                                    font-weight: bold;
                                    text-decoration: underline;
                                    color: black;
                                }
                                .details p {
                                    margin: 0;
                                }
                                /* Table Styles */
                                table { 
                                    width: 100%; 
                                    border-collapse: collapse; 
                                    margin-top: 20px; 
                                }
                                th, td { 
                                    border: 1px solid #000; 
                                    padding: 8px; 
                                    text-align: left; 
                                } 
                            }
                            /* Optional styles for "Time In/Out" formatting */
                            .time-format { font-style: italic; }
                        </style>
                    </head>
                    <body>
                        <!-- Header -->
                        <div class="print-header">
                            <h1>Bicol University</h1>
                            <h2>Rizal St., Legazpi City, Albay</h2>
                            <h3>BU Head SSU Section</h3>
                        </div>
                        <!-- Details Section -->
                        <div class="details">
                            <p><b>Title:</b> User Logs Records</p>
                            <p><b>Print By:</b> ${userFname} ${userLname}</p>
                            <p><b>Date:</b> ${formattedDate} at ${formattedTime}</p>
                        </div>
                        <!-- User Logs Table -->
                        ${tableContent}
                    </body>
                </html>
            `);
            printWindow.document.close();

            // Trigger print dialog and close the window afterward
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
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
