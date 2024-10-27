
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Report Vehicle</title>
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

        <div class="to_report">
            <h3>REPORT A VEHICLE</h3>
            <!-- Display success or error messages -->
            @if ($errors->any())
                <div class="main-error unauthorized_report_error">
                    <p id="errorMessage" class="error-message">
                        <span><i class="bi bi-exclamation-circle"></i></span>
                        {{ $errors->first() }}
                        <a class="cancel-button" onclick="hideMessage('errorMessage')"><i class="bi bi-x"></i></a>
                    </p>
                </div>
            @endif

            @if (session('error'))
                <div class="main-error unauthorized_report_error">
                    <p id="errorMessage" class="error-message">
                        <span><i class="bi bi-exclamation-circle"></i></span>
                        {{ session('error') }}
                        <a class="cancel-button" onclick="hideMessage('errorMessage')"><i class="bi bi-x"></i></a>
                    </p>
                </div>
            @endif

            @if (session('success'))
                <div class="main-success unauthorized_report_success">
                    <p id="successMessage" class="success-message">
                        <span><i class="bi bi-check-circle"></i></span>
                        {{ session('success') }}
                        <a class="cancel-button-success" onclick="hideMessage('successMessage')"><i class="bi bi-x"></i></a>
                    </p>
                </div>
            @endif

            <form action="{{ route('report.vehicle.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="inputs">
                    <div class="input-form">
                        <label for="plate_no">Plate No.</label>
                        <input type="text" placeholder="Plate Number" id="plate_no" name="plate_no" required>
                    </div>

                    <div class="input-form">
                        <label for="location">Location</label>
                        <input type="text" placeholder="location" id="location" name="location" required>
                    </div>

                    <div class="input-form">
                        <label for="vio_type">Violation Type</label>
                        <select id="vio_type" name="vio_type" required>
                            <option value="">Select Violation Type</option>
                            @foreach($violationTypes as $violationType)
                                <option value="{{ $violationType->id }}">{{ $violationType->violation_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="input-form">
                        <label for="date">Date</label>
                        <input type="text" placeholder="" id="date" readonly>
                    </div>

                    <div class="input-form">
                        <label for="time">Time</label>
                        <input type="text" placeholder="" id="time" readonly>
                    </div>

                    <div class="input-form">
                        <label for="report_by">Reported by</label>
                        <input type="hidden" name="report_by" value="{{ Session::get('user')['id'] }}">
                        <input type="text" placeholder="" id="report_by" value="{{ Session::get('user')['fname'] }} {{ Session::get('user')['lname'] }}" readonly>
                    </div>
                    
                    <div class="row2">
                        <div class="input-form2">
                            <label for="photo">Documentation</label>
                            <div class="click_files">
                                <img src="{{ asset('images/upload 1.png') }}" alt="Upload icon" id="upload-icon">
                                <div class="file-label">
                                    <label for="files">Click to Attach Photo</label>
                                </div>
                                <input type="file" id="files" name="photo" accept="image/*" style="display: none;" required>
                            </div>
                        </div>
                    </div>

                    <div class="save_not_btn">
                        <button type="submit" id="submit" class="done">DONE</button>
                        <a class="nav-link" href="{{ url('/index') }}">BACK</a>
                    </div>
                </div>
            </form>
        </div>
        
    </main><!-- End #main -->

    <script src="{{ asset('js/hide_errors_success_unauthorized.js') }}"></script>
    
    <!-- SELECTED IMAGE -->
    <script src="{{ asset('js/selected_img.js') }}"></script>

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/twodisplayed_DateTime.js') }}"></script>
</body>
</html>
