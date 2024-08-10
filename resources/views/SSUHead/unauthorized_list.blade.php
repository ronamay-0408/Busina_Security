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

        <div class="main-title">
            <h3 class="per-title">UNAUTHORIZED VEHICLE</h3>
        </div>

        <div class="content">
            <div class="dropdown-month">
                <label>FILTERING FIELDS</label>

                <div class="filter-container">
                    <div class="filter-item">
                        <label>YEAR</label>
                        <input class="filter-year" type="text" id="year-filter" placeholder="Select Year" readonly>
                        <button class="btn btn-secondary btn-clear" id="clear-year">Clear</button>
                    </div>
                    <div class="filter-item">
                        <label>MONTH</label>
                        <input class="filter-month" type="text" id="month-filter" placeholder="Select Month" readonly>
                        <button class="btn btn-secondary btn-clear" id="clear-month">Clear</button>
                    </div>
                    <div class="filter-item">
                        <label>DAY</label>
                        <input class="filter-day" type="text" id="day-filter" placeholder="Select Day" readonly>
                        <button class="btn btn-secondary btn-clear" id="clear-day">Clear</button>
                    </div>
                </div>
            </div>
            <div class="export-tbn">
                <button class="export-child btn btn-primary">EXPORT</button>
            </div>
        </div>

        <div class="search-bar">
            <input type="text" id="searchInputUnauthorized" placeholder="Search.." name="search">
        </div>

        <div class="head_view_unauthorized_table">
            <table id="unauthorizedTable">
                <thead>
                    <tr>
                        <th>Plate No</th>
                        <th>Full Name</th>
                        <th>Purpose</th>
                        <th>Date and Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unauthorizedRecords as $record)
                        <tr>
                            <td>{{ $record->plate_no }}</td>
                            <td>{{ $record->fullname }}</td>
                            <td>{{ $record->purpose }}</td>
                            <td>{{ $record->created_at->format('F j, Y, g:i a') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main><!-- End #main -->

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
