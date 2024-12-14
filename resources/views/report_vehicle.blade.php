
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
            <form id="reportForm" action="{{ route('report.vehicle.store') }}" method="POST" enctype="multipart/form-data">
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

                    <div class="input-form wv">
                        <label for="date">Date</label>
                        <input type="text" placeholder="" id="date" readonly>
                    </div>

                    <div class="input-form wv">
                        <label for="time">Time</label>
                        <input type="text" placeholder="" id="time" readonly>
                    </div>

                    <div class="input-form wv">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#reportForm').on('submit', function (e) {
                e.preventDefault();  // Prevent the default form submission

                // Show SweetAlert2 loading spinner
                Swal.fire({
                    title: 'Processing...',
                    text: 'Submitting the report...',
                    showConfirmButton: false,  // Hide the confirm button
                    allowOutsideClick: false,  // Prevent closing by clicking outside
                    didOpen: () => {
                        Swal.showLoading();  // Show the loading animation
                    }
                });

                // Send the form data via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: new FormData(this),  // Use FormData to submit form with file
                    processData: false,  // Don't process the data
                    contentType: false,  // Don't set content type
                    success: function (response) {
                        // Hide the loading spinner and show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Violation report submitted successfully.',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();  // Reload the page after success message
                        });
                    },
                    error: function (xhr) {
                        // Hide the loading spinner and show error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: xhr.responseJSON.error || 'An error occurred while submitting the form.',
                            timer: 3000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();  // Reload the page after error message
                        });
                    }
                });
            });
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
