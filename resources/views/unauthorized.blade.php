<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - Unauthorized Vehicle</title>
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

        <div class="to_report">
            <h3>LOG UNAUTHORIZED VEHICLE</h3>

            <h3>QR Code: {{ session('qr', 'Unknown QR Code') }}</h3>
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


            <form action="{{ route('store_unauthorized') }}" method="POST">
                @csrf
                <div class="inputs">
                    <div class="input-form">
                        <label for="plate_no">Plate No.</label>
                        <input type="text" placeholder="LLL-DDDD or DDD-LLL/L-DDD-LL" id="plate_no" name="plate_no" required>
                    </div>

                    <div class="input-form">
                        <label for="fullname">Full Name</label>
                        <input type="text" placeholder="Full Name" id="fullname" name="fullname" required>
                    </div>

                    <!-- <div class="input-form">
                        <label for="purpose">Purpose</label>
                        <input type="text" placeholder="Submission of Document" id="purpose" name="purpose" required>
                    </div> -->

                    <div class="input-form">
                        <label for="date">Date</label>
                        <input type="text" placeholder="" id="date" readonly>
                    </div>

                    <div class="input-form">
                        <label for="time">Time</label>
                        <input type="text" placeholder="" id="time" readonly>
                    </div>

                    <div class="save_not_btn">
                        <button type="submit" id="submit" class="done">DONE</button>
                        <a class="nav-link" href="{{ url('/visitor_scanner') }}">BACK</a>
                    </div>
                </div>
            </form>
        </div>
        
    </main><!-- End #main -->

    <script src="{{ asset('js/hide_errors_success_unauthorized.js') }}"></script>
    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/twodisplayed_DateTime.js') }}"></script>
</body>
</html>
