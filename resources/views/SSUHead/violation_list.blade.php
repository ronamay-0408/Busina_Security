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

    <style>
        /* Custom CSS for pagination */
        .pagination {
            display: flex;
            justify-content: center;
            padding: 0;
            margin: 20px 0;
        }

        .page-item {
            margin: 0 2px;
            padding: 8px 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #007bff;
            cursor: pointer;
        }

        .page-item.active {
            background-color: #09b3e4;
            color: white;
            border-color: rgba(53, 192, 247, 0.3);
        }

        .page-item.disabled {
            color: #ccc;
            cursor: not-allowed;
            pointer-events: none;
        }

        .page-item:hover {
            background-color: #f0f0f0;
        }

        /* Custom CSS for per-page dropdown */
        .per-page-form {
            display: flex;
            align-items: center;
            margin: 0 0 10px 0;
        }

        .per-page-form label {
            margin-right: 10px;
            font-size: 14px;
            color: #333;
        }

        .per-page-form select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }

        .per-page-form select:focus {
            border-color: #007bff;
            outline: none;
        }


        .modal-details {
            padding: 10px;
        }
        .modal-details p {
            margin: 0 0 5px 0;
        }
        .modal-details h3 {
            /* margin: 0; */
            text-align: center;
        }
        .vio-details p {
            margin: 0;
            padding: 0 0 5px 0;
        }

        @media (max-width: 600px) {
            .page-item {
                font-size: 12px;
            }
            
            .content {
                flex-wrap: wrap;
            }
            .dropdown-month {
                flex-wrap: wrap;
            }
            .filter-container {
                flex-wrap: wrap;
            }
            .filter-item {
                gap: 10px;
            }
            .filter-item input {
                width: 100%;
            }

            .modal-content {
                width: 90%;
            }
        }
    </style>
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

        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('head_index') }}">
                    <img src="images/Dashboard Layout.png" alt="">
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link hove" href="{{ route('violation_list') }}">
                    <img src="images/Foul.png" alt="">
                    <span>Violations</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('unauthorized_list') }}">
                    <img src="images/Qr Code.png" alt="">
                    <span>Unauthorized Vehicles</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('ssu_personnel') }}">
                    <img src="images/Foul.png" alt="">
                    <span>SSU Personnels</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('head_guidelines') }}">
                    <img src="images/Driving Guidelines.png" alt="">
                    <span>Guidelines</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('head_account') }}">
                    <img src="images/Account.png" alt="">
                    <span>My Account</span>
                </a>
            </li>
            <li class="nav-item last head-last">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="images/Open Pane.png" alt="">
                    <span>Log Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="date-time"></div>

        <div class="submain">
            <div class="main-title">
                <h3 class="per-title">REPORTED VIOLATIONS</h3>
                <div class="submain-btn">
                    <button type="submit" name="export" value="csv" class="buttons">Export as CSV</button>
                    <button type="submit" class="buttons">Export All Details to CSV</button>
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
                        <p><strong>Date & Time:</strong> <span id="modal-date"></span></p>
                        <p><strong>Plate No:</strong> <span id="modal-plate"></span></p>
                        <p><strong>Violation Type:</strong> <span id="modal-violation"></span></p>
                        <p><strong>Location:</strong> <span id="modal-location"></span></p>
                        <p><strong>Reported By:</strong> <span id="modal-reported"></span></p>
                        <p><strong>Remarks:</strong> <span id="modal-remarks"></span></p>
                    </div>
                    <p><strong>Proof Image:</strong></p>
                    <img id="modal-image" src="" alt="Proof Image" style="width: 100%;"/>
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

            // Modal functionality with event delegation
            $(document).on('click', '.view-btn', function() {
                // Update modal content with data attributes
                $('#modal-image').attr('src', $(this).data('image'));
                $('#modal-date').text($(this).data('date'));
                $('#modal-plate').text($(this).data('plate'));
                $('#modal-violation').text($(this).data('violation'));
                $('#modal-location').text($(this).data('location'));
                $('#modal-reported').text($(this).data('reported'));
                $('#modal-remarks').text($(this).data('remarks'));

                // Show the modal
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
            const exportButton = document.querySelector('.submain-btn button[name="export"]');
            const exportAllButton = document.querySelector('.submain-btn button:not([name="export"])');
            const violationTable = document.getElementById('violationTable');

            function generateCSV(data) {
                // Create a CSV string from the data array
                const csvRows = [];
                const headers = Array.from(data[0].querySelectorAll('th')).map(th => th.textContent);
                csvRows.push(headers.join(','));

                for (const row of data) {
                    const cells = Array.from(row.querySelectorAll('td')).map(td => `"${td.textContent.replace(/"/g, '""')}"`);
                    csvRows.push(cells.join(','));
                }

                return csvRows.join('\n');
            }

            function getCurrentDateString() {
                // Get the current date in YYYY-MM-DD format
                const now = new Date();
                const year = now.getFullYear();
                const month = (now.getMonth() + 1).toString().padStart(2, '0');
                const day = now.getDate().toString().padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            function downloadCSV(csv, filename) {
                const csvFile = new Blob([csv], { type: 'text/csv' });
                const downloadLink = document.createElement('a');
                
                downloadLink.download = filename;
                downloadLink.href = window.URL.createObjectURL(csvFile);
                downloadLink.style.display = 'none';
                document.body.appendChild(downloadLink);
                
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }

            function exportVisibleRows() {
                const visibleRows = Array.from(violationTable.querySelectorAll('tbody tr')).filter(row => row.style.display !== 'none');
                if (visibleRows.length === 0) {
                    alert('No visible rows to export.');
                    return;
                }

                const csv = generateCSV([...violationTable.querySelectorAll('thead tr'), ...visibleRows]);
                const filename = `Violation_Reports_Filtered_${getCurrentDateString()}.csv`;
                downloadCSV(csv, filename);
            }

            function exportAllRows() {
                const allRows = Array.from(violationTable.querySelectorAll('tbody tr'));
                if (allRows.length === 0) {
                    alert('No rows to export.');
                    return;
                }

                const csv = generateCSV([...violationTable.querySelectorAll('thead tr'), ...allRows]);
                const filename = `Violation_Reports_All_${getCurrentDateString()}.csv`;
                downloadCSV(csv, filename);
            }

            exportButton.addEventListener('click', exportVisibleRows);
            exportAllButton.addEventListener('click', exportAllRows);
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
