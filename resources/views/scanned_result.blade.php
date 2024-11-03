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
    <style>
        .unsettle-vio{
            padding: 0px 0px 10px 0px;
        }
        .unsettle-vio h3{
            margin: 0;
            color: rgba(4, 0, 68, 1);
            /* padding: 10px 0px 0px 0px;
            margin-top: 5px; */
        }
        .unsettle_violation{
            display: flex;
            gap: 10px;
        }
        .violation_con{
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .1), 0 2px 4px -1px rgba(0, 0, 0, .06);
            letter-spacing: 1px;
            width: 100%;
        }
        .violation_con span{
            font-weight: 500;
            color: coral;
        }
        .violation_con p{
            font-weight: 500;
            margin: 0;
            /* color: #797501; */
            background-color: #FAFFB8;
        }
        @media (max-width: 600px) {
            .unsettle_violation{
                
                flex-wrap: wrap;
                gap: 10px;
            }
        }
    </style>

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
            <!-- Date-time display -->
        </div>

        <div class="title">
            <h3>NAME : <span>{{ $vehicleOwner->fname }} {{ $vehicleOwner->mname }} {{ $vehicleOwner->lname }}</span></h3>
        </div>

        <div class="title">
            <h3>REGISTERED VEHICLE</h3>
        </div>
        <div class="registered_vehicle">
            @if ($groupedTransactions->isEmpty())
                <p>No registered vehicles found for this owner.</p>
            @else
                @foreach($groupedTransactions as $registrationNo => $group)
                    <div class="vehicle_con">
                        <div class="vehicle_info">
                            <h3>
                                REGISTRATION NUMBER .: 
                                <a href="{{ route('vehicle.info', ['registration_no' => $registrationNo]) }}" class="btn btn-primary">
                                    {{ $registrationNo }}
                                </a>
                            </h3>
                            <p>PLATE NUMBER .: <span>{{ $group->first()->vehicle->plate_no ?? 'N/A' }}</span></p>
                            <p>STICKER EXPIRY .: <span>{{ $group->first()->sticker_expiry }}</span></p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="unsettle-vio">
            <h3>UNSETTLE VIOLATION</h3>
        </div>
        <div class="unsettle_violation">
            @if ($unsettledViolations->isEmpty())
                <p>No unsettled violations found for this owner.</p>
            @else
                @foreach($unsettledViolations as $violation)
                    <div class="violation_con">
                        <span>{{ $violation->created_at->format('Y-m-d') }}</span><br>
                        <span>{{ $violation->violationType->violation_name ?? 'N/A' }}</span><br>
                        <p><span>{{ $violation->remarks }}</span></p>
                        <!-- <p>Reported By: <span>{{ $violation->reportedBy->fname ?? 'N/A' }}</span></p> -->
                    </div>
                @endforeach
            @endif
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
