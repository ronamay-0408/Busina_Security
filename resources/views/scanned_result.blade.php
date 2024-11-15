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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    @include('MainPartials.ssu_sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i> <!-- Moved toggle button here --></div>
            <div class="date-time"></div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

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
                    @if ($loop->first || $group->count() === 1)
                        <div class="vehicle_con">
                            <div class="vehicle_info">
                                <h3>
                                    REGISTRATION NUMBER .: 
                                    <a href="{{ route('vehicle.info', ['registration_no' => $registrationNo]) }}" class="btn btn-primary">
                                        {{ $registrationNo }}
                                    </a>
                                </h3>
                                <p>PLATE NUMBER .: <span>{{ $group->first()->vehicle->plate_no ?? 'N/A' }}</span></p>
                                
                                @php
                                    $stickerExpiry = \Carbon\Carbon::parse($group->first()->sticker_expiry);
                                    $oneMonthBeforeExpiry = $stickerExpiry->subMonth();
                                    $today = \Carbon\Carbon::now();
                                @endphp
                                
                                <p>STICKER EXPIRY .: <span>{{ $stickerExpiry->format('Y-m-d') }}</span></p>

                                @if ($today->gte($oneMonthBeforeExpiry))
                                    <p style="color: red;">Need to renew the vehicle</p>
                                @endif
                            </div>
                        </div>
                    @endif
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
