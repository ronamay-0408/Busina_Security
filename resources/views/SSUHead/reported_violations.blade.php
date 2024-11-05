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

        .search-filter {
            padding: 0px 0px 20px 0px;
        }

        .settled {
            color: #097901;
            background-color: #B9FFB8;
            padding: 0.2em 0.5em;
            border-radius: 4px;
        }

        .not-settled {
            color: #797501;
            background-color: #FAFFB8;
            padding: 0.2em 0.5em;
            border-radius: 4px;
        }

        .clickable-row{
            cursor: pointer;
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
                    <button type="submit" id="exportExcelButton" class="buttons">Export Filtered as Excel</button>
                    <button type="button" id="exportAllButton" class="buttons">Export All as Excel</button>
                </div>
            </div>
            <div class="table-container">
                
                <!-- Search and Filter Inputs -->
                <div class="search-filter" style="display: flex; gap: 8px;">
                    <div class="search-bar">
                        <input type="text" id="searchInput" placeholder="Search by plate no or violation type" class="search-input">
                    </div>
                    
                    <div class="filter-field">
                        <!-- Year Filter -->
                        <select id="yearFilter" class="filter-select">
                            <option value="">Select Year</option>
                            @foreach(range(date('Y'), 2000) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Month Filter -->
                        <select id="monthFilter" class="filter-select">
                            <option value="">Select Month</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">{{ date('F', mktime(0, 0, 0, $month, 1)) }}</option>
                            @endforeach
                        </select>
                        
                        <!-- Day Filter -->
                        <select id="dayFilter" class="filter-select">
                            <option value="">Select Day</option>
                            @foreach(range(1, 31) as $day)
                                <option value="{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}">{{ $day }}</option>
                            @endforeach
                        </select>

                        <select id="remarksFilter" class="filter-select">
                            <option value="">Remarks</option>
                            <option value="1">Not been settled</option>
                            <option value="2">Settled</option>
                        </select>
                    </div>
                </div>

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

                <table class="table">
                    <thead>
                        <tr>
                            <th class="th-class">Plate No</th>
                            <th class="th-class">Violation Type</th>
                            <th class="th-class">Reported At</th>
                            <th class="th-class">Remarks</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach ($data as $row)
                            <tr class="clickable-row" data-url="{{ route('rv_details', ['id' => $row->id]) }}">
                                <td class="td-class">{{ $row->plate_no }}</td>
                                <td class="td-class">{{ $row->violationType->violation_name ?? 'Unknown' }}</td>
                                <td class="td-class">{{ $row->created_at }}</td>
                                <td>
                                    <span class="{{ $row->remarks == 'Settled' ? 'settled' : ($row->remarks == 'Not been settled' ? 'not-settled' : '') }}">
                                        {{ $row->remarks ?? 'Unknown' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="results-info">
                    <p>Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries</p>
                </div>

                <div class="pagination">
                    @if ($data->onFirstPage())
                        <span class="page-item disabled">« Previous</span>
                    @else
                        <a class="page-item" href="{{ $data->previousPageUrl() }}">« Previous</a>
                    @endif

                    @foreach ($data->getUrlRange(1, $data->lastPage()) as $page => $url)
                        @if ($page == $data->currentPage())
                            <span class="page-item active">{{ $page }}</span>
                        @else
                            <a class="page-item" href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($data->hasMorePages())
                        <a class="page-item" href="{{ $data->nextPageUrl() }}">Next »</a>
                    @else
                        <span class="page-item disabled">Next »</span>
                    @endif
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all rows with class .clickable-row
            const rows = document.querySelectorAll('.clickable-row');
            
            rows.forEach(function(row) {
                row.addEventListener('click', function() {
                    const url = row.getAttribute('data-url');
                    if (url) {
                        window.location.href = url;
                    }
                });
            });
        });
    </script>

    <script>
        document.getElementById('searchInput').addEventListener('keyup', filterTable);
        document.getElementById('yearFilter').addEventListener('change', filterTable);
        document.getElementById('monthFilter').addEventListener('change', filterTable);
        document.getElementById('dayFilter').addEventListener('change', filterTable);
        document.getElementById('remarksFilter').addEventListener('change', filterTable);
        
        function filterTable() {
            let searchValue = document.getElementById('searchInput').value.toLowerCase().trim();
            let yearValue = document.getElementById('yearFilter').value;
            let monthValue = document.getElementById('monthFilter').value;
            let dayValue = document.getElementById('dayFilter').value;
            let remarksFilterValue = document.getElementById('remarksFilter').value;

            let rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                let plateNo = row.cells[0].textContent.toLowerCase();
                let violationType = row.cells[1].textContent.toLowerCase();
                let dateCreated = row.cells[2].textContent;
                let remarks = row.cells[3].textContent;

                let dateParts = dateCreated.split(' ')[0].split('-');
                let year = dateParts[0];
                let month = dateParts[1];
                let day = dateParts[2];
                
                let matchesSearch = plateNo.includes(searchValue) || violationType.includes(searchValue);
                let matchesYear = yearValue === '' || year === yearValue;
                let matchesMonth = monthValue === '' || month === monthValue;
                let matchesDay = dayValue === '' || day === dayValue;
                let matchesRemarks = remarksFilterValue === '' ||
                    (remarksFilterValue === '1' && remarks.trim() === 'Not been settled') ||
                    (remarksFilterValue === '2' && remarks.trim() === 'Settled');

                if (matchesSearch && matchesYear && matchesMonth && matchesDay && matchesRemarks) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#exportAllButton').on('click', function() {
                window.location.href = "{{ route('export.excel') }}"; // For exporting all
            });

            $('#exportExcelButton').on('click', function() {
                let searchValue = $('#searchInput').val(); // Ensure this ID matches the search input
                let yearValue = $('#yearFilter').val(); // Ensure this ID matches the year filter
                let monthValue = $('#monthFilter').val(); // Ensure this ID matches the month filter
                let dayValue = $('#dayFilter').val(); // Ensure this ID matches the day filter
                let remarksFilterValue = $('#remarksFilter').val(); // Ensure this ID matches the remarks filter

                // Construct the URL for the filtered export
                let url = "{{ route('export.filtered.excel') }}?search=" + encodeURIComponent(searchValue) +
                        "&year=" + encodeURIComponent(yearValue) +
                        "&month=" + encodeURIComponent(monthValue) +
                        "&day=" + encodeURIComponent(dayValue) +
                        "&remarks=" + encodeURIComponent(remarksFilterValue);

                window.location.href = url; // Redirect to the constructed URL
            });
        });
    </script>


    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
