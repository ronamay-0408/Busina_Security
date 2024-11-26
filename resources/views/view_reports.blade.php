<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Security - View Reports</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/security.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ssu_head.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    @include('MainPartials.ssu_sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i></div>
            <div class="date-time"></div>
        </div>

        <div class="submain">
            <div class="main-title">
                <h3 class="per-title">MY REPORTS</h3>
            </div>

            <div class="search-bar">
                <!-- Search Input -->
                <input type="text" placeholder="Search by Plate Number" name="search" id="search-input">
            </div>

            <!-- Violation List -->
            <div class="myreports-con">
                @include('MainPartials.myviolation', ['violations' => $violations])
            </div>
        </div>
    </main><!-- End #main -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Check if the URL has a 'search' query parameter
            const urlParams = new URLSearchParams(window.location.search);
            const searchQuery = urlParams.get('search');

            // If there's a search query in the URL, populate the search input field with the search query
            if (searchQuery) {
                document.getElementById('search-input').value = searchQuery;
            }

            // Listen for input changes in the search bar
            const searchInput = document.getElementById('search-input');

            searchInput.addEventListener('keyup', function() {
                const searchQuery = searchInput.value;

                // Send the search query via AJAX to the server
                $.ajax({
                    url: "{{ route('view_reports') }}", // Correct route for your reports
                    method: 'GET',
                    data: { search: searchQuery }, // Send the search query to the controller
                    success: function(response) {
                        console.log('AJAX Response:', response); // Log the response data to the console

                        // Ensure the response contains the expected HTML (the entire content for violations, results info, and pagination)
                        if (response.html) {
                            // Replace the existing content with the updated HTML from the AJAX response
                            document.querySelector('.myreports-con').innerHTML = response.html;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("There was an error with the AJAX request:", error);
                        alert('There was an error with the search request. Please try again.');
                    }
                });
            });
        });
    </script>
    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
