<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - My Account</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
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
                <h3 class="per-title">MY ACCOUNT</h3>
            </div>

            <div class="per_report">
                <form action="">
                    <div class="photo">
                        <img src="images/Male User.png" alt="">
                    </div>
                    <div class="inputs">
                        <div class="input-form">
                            <label for="emp_no">Employee Number</label>
                            <input type="text" name="emp_no" value="{{ Session::has('user') ? Session::get('user')['emp_no'] : '' }}" readonly>
                        </div>

                        <div class="input-form">
                            <label for="fname">First Name</label>
                            <input type="text" name="fname" value="{{ Session::has('user') ? Session::get('user')['fname'] : '' }}" readonly>
                        </div>

                        <div class="input-form">
                            <label for="mname">Middle Name</label>
                            <input type="text" name="mname" value="{{ Session::has('user') ? Session::get('user')['mname'] : '' }}" readonly>
                        </div>

                        <div class="input-form">
                            <label for="lname">Last Name</label>
                            <input type="text" name="lname" value="{{ Session::has('user') ? Session::get('user')['lname'] : '' }}" readonly>
                        </div>

                        <div class="input-form">
                            <label for="contact_num">Contact No.</label>
                            <input type="text" name="contact_num" value="{{ Session::has('user') ? Session::get('user')['contact_no'] : '' }}" readonly>
                        </div>

                        <div class="input-form">
                            <label for="email">Email</label>
                            <input type="email" name="email" value="{{ Session::has('user') ? Session::get('user')['email'] : '' }}" readonly>
                        </div>                    
                    </div>
                </form>

                <div class="back-btn4">
                    <a class="nav-link" href="{{ url('/head_index') }}">BACK</a>
                </div>
            </div>
        </div>
    </main><!-- End #main -->

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
