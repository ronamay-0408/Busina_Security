<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - SubUserLogs</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ssu_head.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
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
        <div class="date-time"></div>

        <div class="submain">
            <h3 class="userlog-title">Vehicle Owner Information</h3>
            <div class="owner-info">
                <ul>
                    <li>
                        <span>Full Name :</span> 
                        <span class="deets">{{ $vehicleOwner->fname }} {{ $vehicleOwner->mname }} {{ $vehicleOwner->lname }}</span>
                    </li>
                    <li>
                        <span>Contact Number :</span> 
                        <span class="deets">{{ $vehicleOwner->contact_no }}</span>
                    </li>
                </ul>
                <ul>
                    <li>
                        <span>Driver's License :</span>
                        <span class="deets">{{ $vehicleOwner->driver_license_no }}</span>
                    </li>
                    <li>
                        <span>Owner Type :</span> 
                        <span class="deets">{{ $vehicleOwner->applicantType->type }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <h2>OWNED VEHICLES</h2>
        <div class="submain">
            <div class="vehicles-owned">
                @foreach($vehicleOwner->vehicles as $vehicle)
                    <div class="vehicle">
                        <h3 class="userlog-title">Vehicle Information</h3>
                        <div class="owner-info">
                            <ul>
                                <li>
                                    <span>Plate Number :</span> 
                                    <span class="deets">{{ $vehicle->plate_no }}</span>
                                </li>
                                <li>
                                    <span>Registration Number :</span> 
                                    <span class="deets">
                                        @if($vehicle->transactions)
                                            {{ $vehicle->transactions->registration_no }}
                                        @else
                                            No registration data available.
                                        @endif
                                    </span>
                                </li>
                                <li>
                                    <span>Sticker Expiry :</span> 
                                    <span class="deets">
                                        @if($vehicle->transactions && $vehicle->transactions->sticker_expiry)
                                            {{ \Carbon\Carbon::parse($vehicle->transactions->sticker_expiry)->format('Y-m-d') }}
                                        @else
                                            No sticker expiry data.
                                        @endif
                                    </span>
                                </li>
                            </ul>
                            <ul>
                                <li>
                                    <span>Model Color :</span> 
                                    <span class="deets">{{ $vehicle->model_color }}</span>
                                </li>
                                <li>
                                    <span>Vehicle Status :</span>
                                    <span class="deets">
                                        @if($vehicle->transactions)
                                            {{ $vehicle->transactions->vehicle_status }}
                                        @else
                                            No vehicle status data.
                                        @endif
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </main>

    @include('SSUHead.partials.footer')
    
    <!-- MODAL AND SEARCH JS -->
    <!-- <script src="{{ asset('js/head_violation_modal.js') }}"></script> -->

    <!-- Filtering JS File -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="{{ asset('js/head_violation_filtering.js') }}"></script>

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
