<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - Home Page</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .reports a {
            display: block;
            text-decoration: none;
            color: inherit;
            width: 100%;
            height: 100%;
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
                <a class="nav-link hove" href="{{ route('head_index') }}">
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
            <li class="nav-item last">
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

        <div class="front">
            <div class="asset1">
                <img src="images/Asset1.png">
            </div>
            <div class="front-name">
                <h3>BICOL <span>UNIVERSITY</span></h3>
                <h1>HEAD OF SECURITY</h1>
                <p>Rizal St., Legazpi City, Albay</p>
            </div>
        </div>

        <!-- resources/views/index.blade.php -->
        <div class="reports">
            <a href="">
                <div class="filed">
                    <h3>10</h3>
                    <p>Filed Reports Today</p>
                </div>
            </a>
            
            <a href="">
                <div class="filed2">
                    <h3>5</h3>
                    <p>Recorded Unauthorized Entries</p>
                </div>
            </a>
        </div>

        <div class="btn1">
            <a class="nav-link" href="{{ url('report_vehicle') }}">CREATE A REPORT</a>
        </div>

        <div class="upload">
            <div class="child">
                <div class="violation">
                <a class="nav-link" href="{{ url('report_vehicle') }}"><img src="images/Foul2.png"></a>
                </div>
                <p>Violation Report</p>
            </div>
            <div class="child2">
                <div class="unauthorize">
                    <a class="nav-link" href="{{ url('unauthorized') }}"><img src="images/Box Important.png" alt=""></a>
                </div>
                <p>Unauthorized Vehicle</p>
            </div>
        </div>
    </main><!-- End #main -->

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
