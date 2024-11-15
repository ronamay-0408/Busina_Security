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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
    @include('SSUHead.partials.sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i> <!-- Moved toggle button here --></div>
            <div class="date-time"></div>
        </div>

        <div class="submain">
            <div class="main-title">
                <h3 class="per-title">REPORTED VIOLATIONS</h3>
                <div class="submain-btn">
                    <button type="button" id="exportExcelButton" class="buttons" onclick="exportFiltered()">Export as Filtered Excel</button>
                    <button type="button" id="exportAllButton" class="buttons" onclick="exportAll()">Export All as Excel</button>
                    <button type="button" id="printViolation" class="buttons">Print</button>
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

            // Function to build the URL with filters
            function buildUrl() {
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

            // Apply filter change
            $filters.on('input change', () => fetchData(buildUrl()));

            // Handle pagination
            $(document).on('click', '#paginationLinks a', function(e) {
                e.preventDefault();
                // When clicking pagination, pass the updated URL with filters
                fetchData($(this).attr('href'));
            });

            function fetchData(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $tableContainer.html(response.tableHtml);
                        // Ensure pagination links are updated with filters
                        $paginationLinks.html(response.paginationHtml);
                    }
                });
            }
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
            const remarks = document.getElementById('remarksFilter').value; // Get the selected remarks filter value

            // Log the selected remarks value to ensure it is being captured correctly
            console.log('Selected remarks:', remarks);

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
                input.value = remarks; // Pass the numeric value for remarks (1 or 2)
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

    <script>
        document.getElementById('printViolation').addEventListener('click', function() {
            printFilteredTable();
        });

        function printFilteredTable() {
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const formattedTime = currentDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

            const tableContent = document.getElementById('tableContainer').innerHTML;

            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print Reported Violations</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                            }
                            @media print {
                                /* Hide default browser print header and footer */
                                @page {
                                    margin: 0 20px 20px 20px;
                                }
                                    
                                /* Hide the default header (browser-specific) */
                                .no-print {
                                    display: none;
                                }

                                /* Header Styles */
                                .print-header {
                                    text-align: center;
                                    font-family: Arial, sans-serif;
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
                                .details {
                                    margin: 10px 0;
                                    font-family: Arial, sans-serif;
                                    font-size: 14px;
                                    line-height: 1.5;
                                }
                                .details p {
                                    margin: 0;
                                }
                                .details b {
                                    font-weight: bold;
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
                                    font-size: 14px;
                                }
                                .settled { 
                                    color: #097901ed; 
                                }
                                .not-settled { 
                                    color: #f44336; 
                                }
                            }
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
                            <p><b>Title:</b> Reported Violations</p>
                            <p><b>Print By:</b> 
                                @if(Session::has('user'))
                                    {{ Session::get('user')['fname'] }} {{ Session::get('user')['lname'] }}
                                @else
                                    Unknown User
                                @endif
                            </p>
                            <p><b>Date:</b> {{ now()->format('F j, Y') }} at {{ now()->format('h:i A') }}</p>
                        </div>
                        <!-- Filtered Table Content -->
                        ${tableContent}
                    </body>
                </html>
            `);
            printWindow.document.close();

            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
