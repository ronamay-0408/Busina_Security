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

        <!-- Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <div class="modal-details">
                    <h3>VIOLATION DETAILS</h3>
                    <div class="vio-details">
                        <div class="vio-plate">
                            <h2><span id="modal-plate"></span></h2>
                            <p>Plate Number</p>
                        </div>
                        <div class="vio-second">
                            <!-- <p><strong>Date & Time:</strong><br><span id="modal-date"></span></p>
                            <p><strong>Violation Type:</strong><br><span id="modal-violation"></span></p>
                            <p><strong>Location:</strong><br><span id="modal-location"></span></p>
                            <p><strong>Reported By:</strong><br><span id="modal-reported"></span></p>
                            <p><strong>Remarks:</strong><br><span id="modal-remarks"></span></p> -->

                            <p>
                                <strong>Date & Time:</strong><br>
                                <input type="text" class="form-control" id="modal-date-input" readonly>
                            </p>
                            <p>
                                <strong>Violation Type:</strong><br>
                                <input type="text" class="form-control" id="modal-violation-input" readonly>
                            </p>
                            <p>
                                <strong>Location:</strong><br>
                                <input type="text" class="form-control" id="modal-location-input" readonly>
                            </p>
                            <p>
                                <strong>Reported By:</strong><br>
                                <input type="text" class="form-control" id="modal-reported-input" readonly>
                            </p>
                            <p>
                                <strong>Remarks:</strong><br>
                                <input type="text" class="form-control" id="modal-remarks-input" readonly>
                            </p>

                            <p><strong>Proof Image:</strong></p>
                            <img id="modal-image" src="" alt="Proof Image"/>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    <!-- JAVASCRIPT FOR AUTOMATIC SEARCH AND FILTERING -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchData(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(response) {
                        $('#tableContainer').html(response.tableHtml);
                        $('#paginationLinks').html(response.paginationHtml);
                    }
                });
            }

            function buildUrl() {
                const search = $('#searchInput').val();
                const year = $('#yearFilter').val();
                const month = $('#monthFilter').val();
                const day = $('#dayFilter').val();
                const perPage = $('#per_page').val();
                const url = new URL(window.location.href);

                url.searchParams.set('search', search);
                url.searchParams.set('year', year);
                url.searchParams.set('month', month);
                url.searchParams.set('day', day);
                url.searchParams.set('per_page', perPage);

                return url.toString();
            }

            // Initial fetch
            fetchData(buildUrl());

            // Event listeners for search and filters
            $('#searchInput, #yearFilter, #monthFilter, #dayFilter').on('input change', function() {
                fetchData(buildUrl());
            });

            $('#per_page').on('change', function() {
                fetchData(buildUrl());
            });

            // Handle pagination clicks
            $(document).on('click', '#paginationLinks a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // // Modal functionality with event delegation
            // $(document).on('click', '.view-btn', function() {
            //     // Update modal content with data attributes
            //     $('#modal-image').attr('src', $(this).data('image'));
            //     $('#modal-date').text($(this).data('date'));
            //     $('#modal-plate').text($(this).data('plate'));
            //     $('#modal-violation').text($(this).data('violation'));
            //     $('#modal-location').text($(this).data('location'));
            //     $('#modal-reported').text($(this).data('reported'));
            //     $('#modal-remarks').text($(this).data('remarks'));

            //     // Show the modal
            //     $('#myModal').show();
            // });

            $(document).on('click', '.view-btn', function() {
                // Retrieve data from the clicked element
                const dateValue = $(this).data('date');
                const violationValue = $(this).data('violation');
                const locationValue = $(this).data('location');
                const reportedValue = $(this).data('reported');
                const remarksValue = $(this).data('remarks');
                
                // Populate input fields (for display only)
                $('#modal-image').attr('src', $(this).data('image'));
                $('#modal-plate').text($(this).data('plate'));

                $('#modal-date-input').val(dateValue);
                $('#modal-violation-input').val(violationValue);
                $('#modal-location-input').val(locationValue);
                $('#modal-reported-input').val(reportedValue);
                $('#modal-remarks-input').val(remarksValue);

                // Show the modal (if applicable)
                $('#myModal').show();
            });

            // Close the modal
            $(document).on('click', '.close', function() {
                $('#myModal').hide();
            });

            // Hide the modal if the user clicks outside of it
            $(window).on('click', function(event) {
                if ($(event.target).is('#myModal')) {
                    $('#myModal').hide();
                }
            });
        });
    </script>

    <!-- JAVASCRIPT FOR EXPORT WITHOUT PAGINATION -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const dayFilter = document.getElementById('dayFilter');
            const perPageForm = document.getElementById('per_page');
            const exportCsvButton = document.getElementById('exportCsvButton');
            const exportAllButton = document.getElementById('exportAllButton');

            // Get query parameters
            function getQueryParams(page = 1) {
                const searchText = searchInput.value.trim();
                const selectedYear = yearFilter.value;
                const selectedMonth = monthFilter.value;
                const selectedDay = dayFilter.value;
                const perPage = perPageForm ? perPageForm.value : 10;

                return new URLSearchParams({
                    search: searchText,
                    year: selectedYear,
                    month: selectedMonth,
                    day: selectedDay,
                    per_page: perPage,
                    page: page
                }).toString();
            }

            // Handle the export CSV button click
            function handleExportCsv() {
                const currentPage = new URLSearchParams(window.location.search).get('page') || 1;
                const queryParams = getQueryParams(currentPage);
                const exportUrl = `{{ route('exportViolationCsv') }}?${queryParams}`;
                window.location.href = exportUrl;
            }

            // Handle the export All button click
            function handleExportAll() {
                const queryParams = getQueryParams();
                const exportUrl = `{{ route('exportAllViolationCsv') }}?${queryParams}`;
                window.location.href = exportUrl;
            }

            // Attach event listeners
            exportCsvButton.addEventListener('click', handleExportCsv);
            exportAllButton.addEventListener('click', handleExportAll);

            // Existing code for handling pagination and filters
            function submitFilters(page = 1) {
                const queryParams = getQueryParams(page);

                fetch(`{{ route('violation_list') }}?${queryParams}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('tableContainer').innerHTML = html;
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

            updatePaginationLinks();
        });
    </script>

    <!-- MODAL AND SEARCH JS -->
    <!-- <script src="{{ asset('js/head_violation_modal.js') }}"></script> -->

    <!-- Filtering JS File -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('js/head_violation_filtering.js') }}"></script>

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
