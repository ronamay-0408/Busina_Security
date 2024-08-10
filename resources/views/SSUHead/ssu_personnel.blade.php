<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - SSU Personnel</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ssu_head.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Style the select to look disabled */
        .disabled-dropdown {
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            color: #666;
            cursor: not-allowed; /* Show a not-allowed cursor */
            pointer-events: none; /* Disable interaction */
            /* Optional: Remove default styling */
            -webkit-appearance: none;
            appearance: none;
            padding: 10px;
            border-radius: 5px;
            font-size: 16px;
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
                <a class="nav-link" href="{{ route('unauthorized_list') }}">
                    <img src="images/Qr Code.png" alt="">
                    <span>Unauthorized Vehicles</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link hove" href="{{ route('ssu_personnel') }}">
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
            <h3 class="per-title">SSU PERSONNELS</h3>
        </div>

        <div class="content">
            <div class="search-bar head-search">
                <input type="text" id="searchInputSSU" placeholder="Search.." name="search">
            </div>
            <div class="ssu-buttons">
                <div class="export-tbn">
                    <button class="export-child" onclick="exportTableToCSV()">EXPORT</button>
                </div>

                <div class="add-new">
                    <img src="images/plus.png" alt="Add New">
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="main-error head-main-error">
            <p id="errorMessage" class="error-message">
                <span><i class="bi bi-exclamation-circle"></i></span>
                {{ $errors->first() }}
                <a class="cancel-button" onclick="hideErrorMessage()"><i class="bi bi-x"></i></a>
            </p>
        </div>
        @endif

        @if (session('error'))
        <div class="main-error head-main-error">
            <p id="errorMessage" class="error-message">
                <span><i class="bi bi-exclamation-circle"></i></span>
                {{ session('error') }}
                <a class="cancel-button" onclick="hideErrorMessage()"><i class="bi bi-x"></i></a>
            </p>
        </div>
        @endif

        @if (session('success'))
        <div class="main-success head-main-success">
            <p id="successMessage" class="success-message">
                <span><i class="bi bi-check-circle"></i></span>
                {{ session('success') }}
                <a class="cancel-button-success" onclick="hideSuccessMessage()"><i class="bi bi-x"></i></a>
            </p>
        </div>
        @endif

        <div class="head_view_ssu_table">
            <table id="ssuTable">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Middle Name</th>
                        <th>Contact Number</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($authorizedUsers as $user)
                        <tr>
                            <td>{{ $user->fname }}</td>
                            <td>{{ $user->lname }}</td>
                            <td>{{ $user->mname }}</td>
                            <td>{{ $user->contact_no }}</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="blur-bg-overlay"></div>
        <div class="form-popup">
            <span class="close-btn">&times;</span>
            <div class="form-box">
                <div class="form-content">
                    <h2>Add New User</h2>

                    <form action="{{ route('ssu_personnel') }}" method="post" onsubmit="return validateEmail()">
                        @csrf
                        <div class="inputs1">
                            <div class="input-form1">
                                <label for="fname">First Name</label>
                                <input type="text" placeholder="Juan" id="fname" name="fname" required>
                            </div>

                            <div class="input-form1">
                                <label for="lname">Last Name</label>
                                <input type="text" placeholder="Santos" id="lname" name="lname" required>
                            </div>

                            <div class="input-form1">
                                <label for="mname">Middle Name</label>
                                <input type="text" placeholder="Carlos" id="mname" name="mname">
                            </div>

                            <div class="input-form1">
                                <label for="contact">Contact #</label>
                                <input type="text" placeholder="Contact Number" id="contact" name="contact" required>
                            </div>

                            <div class="input-form1">
                                <label for="email">Email</label>
                                <input type="email" placeholder="juancarlossantos@gmail.com" id="email" name="email" pattern="[a-z0-9._%+-]+@gmail\.com$" required>
                            </div>

                            <div class="input-form2">
                                <small id="email-error" class="error-message" style="display: none;">Please enter a valid Gmail email address.</small>
                            </div>

                            <div class="input-form1">
                                <label for="user_type">User Type</label>
                                <select id="user_type" name="user_type" class="disabled-dropdown" required>
                                    <option value="2" selected>Security Personnel</option>
                                    <!-- Add more options as needed -->
                                </select>
                            </div>
                            
                            <div class="submit">
                                <button type="submit" id="submit" class="done">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </main><!-- End #main -->

    <script src="{{ asset('js/head_ssu_search.js') }}"></script>

    <script src="{{ asset('js/ssu_export.js') }}"></script>

    <!-- ERROR AND SUCCESS -->
    <script src="{{ asset('js/error_success_message.js') }}"></script>

    <script src="{{ asset('js/validate_email.js') }}"></script>

    <script src="{{ asset('js/adduser_popup.js') }}"></script>

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
