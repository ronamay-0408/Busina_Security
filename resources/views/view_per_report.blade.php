<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Reported Vehicle Info</title>
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

        <!-- resources/views/view_per_report.blade.php -->
        <div class="per_report">
            <h3 class="per-title">PLATE NO.:  <span>{{ $violation->plate_no }}</span></h3>

            <form>
                <div class="inputs">

                    <div class="input-form">
                        <label for="location">Location</label>
                        <input type="text" value="{{ $violation->location }}" id="location" name="location" required>
                    </div>

                    <div class="input-form">
                        <label for="vio_type">Violation Type</label>
                        <input type="text" value="{{ $violation->violationType->violation_name }}" id="vio_type" name="vio_type" required>
                    </div>

                    <div class="input-form">
                        <label for="date">Date</label>
                        <input type="text" value="{{ $violation->created_at->format('m-d-Y') }}" id="date" required>
                    </div>

                    <div class="input-form">
                        <label for="time">Time</label>
                        <input type="text" value="{{ $violation->created_at->format('H:i:s') }}" id="time" required>
                    </div>

                    <div class="input-form">
                        <label for="report_by">Reported by</label>
                        <input type="text" value="{{ $violation->reportedBy->full_name ?? 'N/A' }}" id="report_by" required>
                    </div>

                    <div class="row2">
                        <div class="input-form2">
                            <label for="photo">Documentation</label>
                            @if($violation->proof_image)
                                <div class="click_files2">
                                    <img src="{{ asset('storage/' . $violation->proof_image) }}" alt="Proof Image">
                                    <!-- <img src="{{ asset('storage/app/public/' . $violation->proof_image) }}" alt="Proof Image"> On Hostinger-->
                                </div>
                            @else
                                <p>No image available</p>
                            @endif
                        </div>
                    </div>
                </div>
            </form>

            <div class="back-btn">
                <a class="nav-link" href="{{ url('/view_reports') }}">BACK</a>
            </div>
        </div>
    </main><!-- End #main -->


    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
