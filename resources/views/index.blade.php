<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Home Page</title>
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
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="info">
                @if(Session::has('user'))
                    <h2>{{ Session::get('user')['fname'] }} {{ Session::get('user')['lname'] }}</h2>
                    <h3>{{ Session::get('user')['email'] }}</h3>
                @endif
            </div>
        </div>

        @include('MainPartials.ssu_sidebar')
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
                <h1>SECURITY</h1>
                <p>Rizal St., Legazpi City, Albay</p>
            </div>
        </div>

        <!-- resources/views/index.blade.php -->
        <div class="reports">
            <a href="{{ route('view_reports') }}">
                <div class="filed">
                    <h3>{{ $filedReportsToday }}</h3>
                    <p>Filed Reports Today</p>
                </div>
            </a>
            
            <a href="{{ route('visitor_scanner') }}">
                <div class="filed2">
                    <h3>{{ $unauthorizedEntriesToday }}</h3>
                    <p>Unauthorized Entries</p>
                </div>
            </a>
        </div>

        <div class="btn1">
            <a class="nav-link" href="{{ url('report_vehicle') }}">CREATE A REPORT</a>
        </div>

        <!-- <div class="upload">
            <div class="child">
                <a href="{{ url('report_vehicle') }}">
                    <div class="violation">
                        <a class="nav-link" href="{{ url('report_vehicle') }}"><img src="images/Foul2.png"></a>
                    </div>
                    <p>Violation Report</p>
                </a>
            </div>
            <div class="child2">
                <div class="unauthorize">
                    <a class="nav-link" href="{{ url('visitor_scanner') }}"><img src="images/Box Important.png" alt=""></a>
                </div>
                <p>Unauthorized Vehicle</p>
            </div>
        </div> -->

        <div class="upload">
            <a href="{{ url('report_vehicle') }}">
                <div class="child">
                    <div class="violation">
                        <img src="images/Foul2.png">
                    </div>
                    <p>Violation Report</p>
                </div>
            </a>
            
            <a href="{{ url('visitor_scanner') }}">
                <div class="child">
                    <div class="unauthorize">
                        <img src="images/Box Important.png">
                    </div>
                    <p>Unauthorized Vehicle</p>
                </div>
            </a>
        </div>

    </main><!-- End #main -->

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
