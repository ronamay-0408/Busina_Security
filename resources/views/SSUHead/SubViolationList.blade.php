<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - Violation Details</title>
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
    <style>
        .subsubmain{
            display: flex;
        }
        .owner-info{
            flex: 3;
        }
        .sub-proof-image{
            flex: 2;
        }
        .sub-proof-image h3{
            font-weight: 550;
            margin: 0;
            color: #566a7f;
            font-size: 18px;
            padding: 10px 0px;
        }
        @media (max-width: 600px) {
            .subsubmain{
                display: flow;
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
            <div class="main-title">
                <h3 class="per-title">VIOLATION DETAILS</h3>
            </div>
            <div class="subsubmain">
                <div class="owner-info">
                    <ul>
                        <li>
                            <span>Plate No :</span> 
                            <span class="deets">{{ $violation->plate_no }}</span>
                        </li>
                        <li>
                            <span>Violation Name :</span> 
                            <span class="deets">{{ $violation->violationType ? $violation->violationType->violation_name : 'N/A' }}</span>
                        </li>
                        <li>
                            <span>Reported By :</span>
                            <span class="deets">{{ $violation->reportedBy ? $violation->reportedBy->getFullNameAttribute() : 'N/A' }}</span>
                        </li>
                        <li>
                            <span>Reported At :</span> 
                            <span class="deets">{{ $violation->created_at->format('Y-m-d H:i:s') }}</span>
                        </li>
                        <li>
                            <span>Location :</span>
                            <span class="deets">{{ $violation->location }}</span>
                        </li>
                    </ul>
                </div>
                <div class="sub-proof-image">
                    <h3>Proof Image</h3>
                    @if($violation->proof_image)
                        <img src="{{ asset('storage/' . $violation->proof_image) }}" alt="Proof Image" style="max-width: 100%; height: auto;">
                    @else
                        <span class="deets">No proof image available</span>
                    @endif
                </div>
            </div>
            <div class="back-btn3">
                <a class="nav-link" href="{{ url('/report_violation_list') }}">BACK</a>
            </div>
        </div>
    </main><!-- End #main -->

    @include('SSUHead.partials.footer')

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
