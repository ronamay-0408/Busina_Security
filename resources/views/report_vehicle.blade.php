
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
            <h3>REPORT A VEHICLE</h3>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Pass session data safely into JavaScript
        var successMessage = "{{ session('success') }}";
        var errorMessage = "{{ session('error') }}";
        var validationErrors = "{{ $errors->first() }}";

        // Trigger SweetAlert2 based on the session data
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: successMessage,
                timer: 3000,
                showConfirmButton: false
            });
        }

        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessage,
                timer: 3000,
                showConfirmButton: false
            });
        }

        if (validationErrors) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: validationErrors,
                timer: 3000,
                showConfirmButton: false
            });
        }

        // To show a simple processing message on form submit
        const form = document.querySelector('form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();  // Prevent the form from submitting right away

            // Show SweetAlert processing message instantly
            Swal.fire({
                title: 'Processing...',
                text: 'Sending the report...',
                showConfirmButton: false,
                allowOutsideClick: false
            });

            // Submit the form immediately after showing the message
            form.submit();  // Proceed with form submission right after showing the alert
        });
    </script>
    
    <script src="{{ asset('js/hide_errors_success_unauthorized.js') }}"></script>
    <!-- SELECTED IMAGE -->
    <script src="{{ asset('js/selected_img.js') }}"></script>
    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <!-- DATE AND TIME -->
    <script src="{{ asset('js/twodisplayed_DateTime.js') }}"></script>
</body>
</html>
