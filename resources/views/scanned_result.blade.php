<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Scanned Result</title>
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
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="date-time">
        </div>

        <div class="title">
            <h3>NAME : <span>{{ $vehicleOwner->fname }} {{ $vehicleOwner->mname }} {{ $vehicleOwner->lname }}</span></h3>
        </div>

        <div class="title">
            <h3>REGISTERED VEHICLE</h3>
        </div>
        <!-- resources/views/scanned_result.blade.php -->
        <div class="registered_vehicle">
            @foreach($transactions as $transaction)
                <div class="vehicle_con">
                    <div class="vehicle_info">
                        <h3>
                            REGISTRATION NUMBER .: 
                            <a href="{{ route('vehicle.info', ['registration_no' => $transaction->registration_no]) }}" class="btn btn-primary">
                                {{ $transaction->registration_no }}
                            </a>
                        </h3>
                        <p>PLATE NUMBER .: <span>{{ $transaction->vehicle->plate_no }}</span></p>
                        <p>STICKER EXPIRY .: <span>{{ $transaction->sticker_expiry }}</span></p>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="back-btn3">
            <a class="nav-link" href="{{ url('/scanned_qr') }}">SCANNER</a>
        </div>
    </main><!-- End #main -->


    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
