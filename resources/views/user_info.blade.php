<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Register User Vehicle Info</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">
        <div class="bar">
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div>
        
        <div class="logo">
            <img src="images/BUsina logo (1) 2.png" alt="">
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

        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('index') }}">
                    <img src="images/Dashboard Layout.png" alt="">
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('view_reports') }}">
                    <img src="images/Foul.png" alt="">
                    <span>My Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('scanned_qr') }}">
                    <img src="images/Qr Code.png" alt="">
                    <span>Scanned QR Code</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('guidelines') }}">
                    <img src="images/Driving Guidelines.png" alt="">
                    <span>Guidelines</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('myaccount') }}">
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

        <div class="per_report">
            <h3 class="per-title">REGISTRATION NO.:  <span>[No.]</span></h3>
            
            <form action="">
                <div class="inputs">

                    <div class="input-form">
                        <label for="name">Name</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="emp-id">Employee ID</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="type">Type</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="Model">Model</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="color">Color</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="plate_num">Plate No.</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="or-cr-num">OR/CR No.</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="expiry_date">Expiration Date</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="dl_num">Driver’s License No.</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="dl_expiry_date">Expiration Date</label>
                        <input type="text" placeholder=""  required>
                    </div>

                    <div class="input-form">
                        <label for="cor_copy">Copy of COR</label>
                        <input type="text" placeholder=""  required>
                        <button class="view">View</button>
                    </div>

                    <div class="input-form">
                        <label for="date_issued">Date Issued</label>
                        <input type="text" placeholder=""  required>
                    </div>                    
                </div>
            </form>

            <div class="back-btn3">
                <a class="nav-link" href="{{ url('/') }}">BACK</a>
            </div>
        </div>
    </main><!-- End #main -->

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
