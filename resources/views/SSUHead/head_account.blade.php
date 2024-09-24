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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        <div class="account-mainsub">
            <div class="account-left">
                <div class="left-sub">
                    <img src="images/Male User.png" alt="">
                    <h3>{{ Session::has('user') ? Session::get('user')['fname'] : '' }} {{ Session::has('user') ? Session::get('user')['lname'] : '' }}</h3>
                    <p>Head of Security Service Unit</p>
                    <h3>{{ Session::has('user') ? Session::get('user')['emp_no'] : '' }}</h4>
                </div>
            </div>
            <div class="account-right">
                <div class="account-btn nav-tabs-bordered" role="tablist">
                    <div class="sub-btn">
                        <button class="nav-link2 new-active" id="overviewBtn">Overview</button>
                    </div>
                    <div class="sub-btn">
                        <button class="nav-link2" id="changePassBtn">Change Password</button>
                    </div>
                </div>

                <!-- Overview Section -->
                <div class="Overview" id="overviewSection">
                    <h3>Profile Details</h3>
                    <div class="account-sub">
                        <h4>Full Name</h4>
                        <p>{{ Session::has('user') ? Session::get('user')['fname'] : '' }} {{ Session::has('user') ? Session::get('user')['mname'] : '' }} {{ Session::has('user') ? Session::get('user')['lname'] : '' }}</p>
                        <hr class="dark horizontal my-0">
                    </div>
                    <div class="account-sub">
                        <h4>Email</h4>
                        <p>{{ Session::has('user') ? Session::get('user')['email'] : '' }}</p>
                        <hr class="dark horizontal my-0">
                    </div>
                    <div class="account-sub">
                        <h4>Contact No.</h4>
                        <p>{{ Session::has('user') ? Session::get('user')['contact_no'] : '' }}</p>
                        <hr class="dark horizontal my-0">
                    </div>
                    <div class="account-sub">
                        <h4>Password</h4>
                        <p>**********</p> <!-- Display a masked password -->
                        <hr class="dark horizontal my-0">
                    </div>
                </div>

                <!-- Change Password Section -->
                <div class="ChangePass" id="changePassSection" style="display:none;">
                    <!-- Change Password Form -->
                    <form method="post" action="{{ route('change.password') }}">
                        @csrf
                        <div class="row mb-3">
                            <label>Current Password</label>
                            <div class="pass-input position-relative">
                                <input name="current_password" type="password" class="form-control" required id="currentPassword">
                                <i class="fas fa-eye" onclick="togglePasswordVisibility('currentPassword', this)"></i>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label>New Password</label>
                            <div class="pass-input position-relative">
                                <input name="new_password" type="password" class="form-control" required id="new_pass">
                                <i class="fas fa-eye" onclick="togglePasswordVisibility('new_pass', this)"></i>
                            </div>
                            <div id="passwordStrength"></div>
                        </div>

                        <div class="row mb-3">
                            <label>Re-enter New Password</label>
                            <div class="pass-input position-relative">
                                <input name="new_password_confirmation" type="password" class="form-control" required id="con_pass">
                                <i class="fas fa-eye" onclick="togglePasswordVisibility('con_pass', this)"></i>
                            </div>
                            <div id="confirmPasswordError" style="display:none;">Passwords do not match</div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </form>

                    @if(session('success'))
                        <div id="successMessage" class="alert alert-success" style="display:block;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div id="errorMessage" class="alert alert-danger" style="display:block;">
                            {{ implode(', ', $errors->all()) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- <div class="submain">
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
        </div> -->
    </main><!-- End #main -->

    <script src="{{ asset('js/ChangePasswordConditions.js') }}"></script>

    <script src="{{ asset('js/NavigatingSection.js') }}"></script>

    @include('SSUHead.partials.footer')

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>