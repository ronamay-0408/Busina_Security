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
            background-color: #007bff;
            color: white;
            border-color: #007bff;
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
        }

        #unauthorizedTable tr:nth-child(even) {
            background-color: #f2f2f2;
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
                <a class="nav-link" href="{{ route('violation_list') }}">
                    <img src="images/Foul.png" alt="">
                    <span>Violations</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link hove" href="{{ route('unauthorized_list') }}">
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
        <div class="date-time">
        </div>

        <div class="submain">
            <div class="main-title">
                <h3 class="per-title">UNAUTHORIZED VEHICLE REPORT</h3>
                <div class="submain-btn">
                    <button type="submit" name="export" value="csv" class="buttons">
                        Export as CSV
                    </button>
                    <button type="submit" name="export" value="all" class="buttons"> <!-- Updated value -->
                        Export All Details to CSV
                    </button>
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

                fetch(`{{ route('unauthorized_list') }}?${queryParams}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('unauthorized-data').innerHTML = html;
                    updatePaginationLinks(); // Ensure pagination links are updated with the correct URL
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

            searchInput.addEventListener('input', debounce(submitFilters, 300));
            yearFilter.addEventListener('change', submitFilters);
            monthFilter.addEventListener('change', submitFilters);
            dayFilter.addEventListener('change', submitFilters);
            perPageForm.addEventListener('change', submitFilters);

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

    <!-- JAVASCRIPT FOR EXPORT -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const exportCsvButton = document.querySelector('button[name="export"][value="csv"]');
            const exportAllButton = document.querySelector('button[name="export"][value="all"]');
            const unauthorizedTable = document.getElementById('unauthorizedTable');
            
            function exportToCsv(filename, rows) {
                const csvContent = rows.map(row => row.join(",")).join("\n");
                const csvBlob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const csvUrl = URL.createObjectURL(csvBlob);
                const link = document.createElement('a');
                link.setAttribute('href', csvUrl);
                link.setAttribute('download', filename);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }

            function getFilteredTableData() {
                const rows = Array.from(unauthorizedTable.querySelectorAll('tbody tr'));
                let data = [];
                
                data.push(['Date', 'Plate No', 'Time In', 'Time Out']); // Header row
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        data.push(Array.from(row.children).map(cell => cell.textContent.trim()));
                    }
                });
                
                return data;
            }

            function getAllTableData() {
                const rows = Array.from(unauthorizedTable.querySelectorAll('tbody tr'));
                let data = [];
                
                data.push(['Date', 'Plate No', 'Time In', 'Time Out']); // Header row
                rows.forEach(row => {
                    data.push(Array.from(row.children).map(cell => cell.textContent.trim()));
                });
                
                return data;
            }

            function handleExportClick(includeAll) {
                const filename = `Unauthorized_Vehicle_Report_${new Date().toISOString().split('T')[0]}.csv`;
                const tableData = includeAll ? getAllTableData() : getFilteredTableData();
                if (tableData.length > 1) { // Check if there is data other than header
                    exportToCsv(filename, tableData);
                } else {
                    alert('No data to export');
                }
            }

            if (exportCsvButton) {
                exportCsvButton.addEventListener('click', () => handleExportClick(false));
            } else {
                console.error('Export CSV button not found');
            }
            
            if (exportAllButton) {
                exportAllButton.addEventListener('click', () => handleExportClick(true));
            } else {
                console.error('Export All button not found');
            }
        });
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
