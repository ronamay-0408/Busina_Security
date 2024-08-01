<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Busina Head Security - Home Page</title>
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <meta content="" name="description">
    <meta content="" name="keywords">
    
    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/security.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Add these scripts before the closing </body> tag -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>


    <style>
        .reports {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 2rem;
            gap: 20px;

        }
        .reports .main-box {
            background-color: white;
            padding: 15px 20px;
            border-radius: 10px;
            /* border: 0.5px solid rgba(0, 0, 0, .125); */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .1), 0 2px 4px -1px rgba(0, 0, 0, .06);
            width: calc(100% / 4 - 15px);
        }
        .count-box p {
            margin: 0;
            padding: 5px 0px;
            font-size: 1rem;
            font-weight: 300;
        }

        .info-box {
            text-align: right !important;
        }
        .info-box p{
            margin: 0;
            font-size: .875rem !important;
            font-weight: 300;
            padding: 0px 0px 0px 65px;
        }
        
        .info-box h2{
            margin: 0;
        }

        .icon-box {
            position: absolute;
            margin-top: -1.5rem;
        }
        .icon-box i{
            /* background-color: #8bc34a8a; */
            padding: 15px 20px;
            font-size: 1.5rem;
            color: white;
            /* background-image: linear-gradient(195deg, #42424a, #191919); */
            background-image: linear-gradient(195deg, #03A9F4, #FF9800);
            border-radius: 10px;
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, .14), 0 7px 10px -5px rgba(64, 64, 64, .4) !important;
        }
        
        .count-box span{
            font-weight: 550;
            color: green;
        }

        hr {
            border-top: none;
            height: 1px;
            color: inherit;
            border: 0;
            opacity: .25;
        }
        hr.horizontal {
            background-color: transparent;
        }
        hr.horizontal.dark {
            background-image: linear-gradient(90deg, transparent, rgba(0, 0, 0, .4), transparent);
        }

        .increase{
            color: green !important;
        }
        .decrease{
            color: red !important;
        }

        .charts{
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-top: 2rem;
            gap: 20px;
            padding-bottom: 20px;
        }

        .charts .chart-main-box {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            /* border: 0.5px solid rgba(0, 0, 0, .125); */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, .1), 0 2px 4px -1px rgba(0, 0, 0, .06);
            width: calc(100% / 2 - 15px);
        }

        .chart-main-box .sub-box h3{
            margin: 0;
            padding: 0px 0px 10px 0px;
        }
        /* Responsive Media Query */
        @media (max-width: 1240px) {
            .reports .main-box{
                width: calc(100% / 2 - 15px);
                margin-bottom: 15px;
            }
            .charts .chart-main-box {
                width: 100%;
            }
        }
        @media (max-width: 650px) {
            .reports .main-box{
                width: 100%;
                margin-bottom: 15px;
            }
            
        }

        .chart-subsub-box{
            display: flex;
            gap: 20px;
            justify-content: space-between;
        }
        .chart-sub3-box{
            display: flex;
            gap: 20px;
        }
        .chart-option {
            position: relative;
        }
        .chart-title{
            align-items: end;
        }
        .chart-dropdown {
            font-size: 14px;
            font-weight: 550;
            font-family: 'Poppins';
            border-radius: 10px;
            padding: 0px 5px;
        }
        .chart-export i {
            font-size: 20px;
            cursor: pointer;
        }

    </style>
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

        <ul class="sidebar-nav" id="sidebar-nav">
            <li class="nav-item">
                <a class="nav-link hove" href="{{ route('head_index') }}">
                    <img src="images/Dashboard Layout.png" alt="">
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('violation_list') }}">
                    <img src="images/Foul.png" alt="">
                    <span>Violations</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('unauthorized_list') }}">
                    <img src="images/Driving Guidelines.png" alt="">
                    <span>Unauthorized Vehicles</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('ssu_personnel') }}">
                    <img src="images/Foul.png" alt="">
                    <span>SSU Personnels</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('head_guidelines') }}">
                    <img src="images/Driving Guidelines.png" alt="">
                    <span>Guidelines</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('head_account') }}">
                    <img src="images/Account.png" alt="">
                    <span>My Account</span>
                </a>
            </li>
            <li class="nav-item last">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <img src="images/Open Pane.png" alt="">
                    <span>Log Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="date-time">
        </div>

        <div class="front">
            <div class="asset1">
                <img src="images/Asset1.png">
            </div>
            <div class="front-name">
                <h3>BICOL <span>UNIVERSITY</span></h3>
                <h1>HEAD OF SECURITY</h1>
                <p>Rizal St., Legazpi City, Albay</p>
            </div>
        </div>

        <!-- resources/views/SSUHead/head_index.blade.php -->
        <div class="reports">
            <!-- Existing report boxes -->
            <div class="main-box">
                <div class="sub-box">
                    <div class="icon-box">
                        <i class="bi bi-file-plus-fill"></i>
                    </div>
                    <div class="info-box">
                        <p>Today's Violation Report</p>
                        <h2>{{ $totalViolationsToday }}</h2>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="count-box">
                        <p>
                            <span  class="{{ $violationDifference >= 0 ? 'increase' : 'decrease' }}" >{{ $violationDifference >= 0 ? '+' : '-' }}{{ abs($violationDifference) }}</span>
                            Than Yesterday
                        </p>
                    </div>
                </div>
            </div>
            <div class="main-box">
                <div class="sub-box">
                    <div class="icon-box">
                        <i class="bi bi-car-front-fill"></i>
                    </div>
                    <div class="info-box">
                        <p>Today's Unauthorized Vehicle</p>
                        <h2>{{ $totalUnauthorizedVehiclesToday }}</h2>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="count-box">
                        <p>
                            <span  class="{{ $unauthorizedVehicleDifference >= 0 ? 'increase' : 'decrease' }}">{{ $unauthorizedVehicleDifference >= 0 ? '+' : '-' }}{{ abs($unauthorizedVehicleDifference) }}</span> 
                            Than Yesterday
                        </p>
                    </div>
                </div>
            </div>
            <div class="main-box">
                <div class="sub-box">
                    <div class="icon-box">
                        <i class="bi bi-person-add"></i>
                    </div>
                    <div class="info-box">
                        <p>SSU Personnels</p>
                        <h2>{{ $totalAuthorizedUsers }}</h2>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="count-box">
                        <p><span>{{ $totalAuthorizedUsersYesterday }}</span> Added Yesterday</p>
                    </div>
                </div>
            </div>
            <div class="main-box">
                <div class="sub-box">
                    <div class="icon-box">
                        <i class="bi bi-archive-fill"></i>
                    </div>
                    <div class="info-box">
                        <p>Today's Vehicle Logs</p>
                        <h2>100</h2>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="count-box">
                        <p><span>+5 </span> Than Yesterday</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="charts">
            <div class="chart-main-box">
                <div class="sub-box">
                    <div class="chart-subsub-box">
                        <div class="chart-sub3-box">
                            <div class="chart-title">
                                <h3>Violation Reports Over the Last Week</h3>
                            </div>
                            <div class="chart-option">
                                <select id="timeRange" class="chart-dropdown">
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-export" id="chart-violation-export">
                            <i class="bi bi-cloud-arrow-down-fill"></i>
                        </div>
                    </div>
                    <canvas id="violationChart"></canvas>
                </div>
            </div>
            <div class="chart-main-box">
                <div class="sub-box">
                    <div class="chart-subsub-box">
                        <div class="chart-sub3-box">
                            <div class="chart-title">
                                <h3>Unauthorized Vehicles Over the Last Week</h3>
                            </div>
                            <div class="chart-option">
                                <select id="timeRangeUnauthorized" class="chart-dropdown">
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-export" id="chart-unauthorized-export">
                            <i class="bi bi-cloud-arrow-down-fill"></i>
                        </div>
                    </div>
                    <canvas id="unauthorizedChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Pass PHP data to JavaScript -->
        <script>
            window.chartData = {
                dates: <?php echo json_encode($dates); ?>,
                violationCounts: <?php echo json_encode($violationCounts); ?>,
                unauthorizedCounts: <?php echo json_encode($unauthorizedCounts); ?>,
            };
        </script>

        <!-- Add these scripts before the closing </body> tag -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

        <script>
            const { dates, violationCounts, unauthorizedCounts } = window.chartData;

            const ctxViolation = document.getElementById('violationChart').getContext('2d');
            const ctxUnauthorized = document.getElementById('unauthorizedChart').getContext('2d');

            const create3DGradient = (ctx, color1, color2) => {
                const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                gradient.addColorStop(0, color1);
                gradient.addColorStop(1, color2);
                return gradient;
            };

            let violationChart = new Chart(ctxViolation, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Violations',
                        data: violationCounts,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `Violations: ${tooltipItem.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : '';
                                }
                            }
                        }
                    }
                }
            });

            let unauthorizedChart = new Chart(ctxUnauthorized, {
                type: 'bar',
                data: {
                    labels: dates,
                    datasets: [{
                        label: 'Unauthorized Vehicles',
                        data: unauthorizedCounts,
                        backgroundColor: create3DGradient(ctxUnauthorized, 'rgba(54, 162, 235, 0.8)', 'rgba(54, 162, 235, 0.3)'),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return `Unauthorized Vehicles: ${tooltipItem.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : '';
                                }
                            }
                        }
                    }
                }
            });

            function aggregateDataByMonth(dates, counts) {
                const months = Array(12).fill(0);
                dates.forEach((date, index) => {
                    const month = new Date(date).getMonth();
                    months[month] += counts[index];
                });
                return months;
            }

            function updateViolationChart(labels, data) {
                violationChart.data.labels = labels;
                violationChart.data.datasets[0].data = data;
                violationChart.update();
            }

            function updateUnauthorizedChart(labels, data) {
                unauthorizedChart.data.labels = labels;
                unauthorizedChart.data.datasets[0].data = data;
                unauthorizedChart.update();
            }

            function handleViolationTimeRangeChange() {
                const timeRange = document.getElementById('timeRange').value;

                if (timeRange === 'month') {
                    const monthLabels = Array.from({ length: 12 }, (v, i) => new Date(0, i).toLocaleString('default', { month: 'short' }));
                    const monthlyViolationCounts = aggregateDataByMonth(dates, violationCounts);
                    updateViolationChart(monthLabels, monthlyViolationCounts);

                    document.querySelector('.chart-title h3').textContent = 'Violation Reports Over the Last Year';
                } else {
                    updateViolationChart(dates, violationCounts);
                    document.querySelector('.chart-title h3').textContent = 'Violation Reports Over the Last Week';
                }
            }

            function handleUnauthorizedTimeRangeChange() {
                const timeRangeUnauthorized = document.getElementById('timeRangeUnauthorized').value;

                if (timeRangeUnauthorized === 'month') {
                    const monthLabels = Array.from({ length: 12 }, (v, i) => new Date(0, i).toLocaleString('default', { month: 'short' }));
                    const monthlyUnauthorizedCounts = aggregateDataByMonth(dates, unauthorizedCounts);
                    updateUnauthorizedChart(monthLabels, monthlyUnauthorizedCounts);

                    document.querySelectorAll('.chart-title h3')[1].textContent = 'Unauthorized Vehicles Over the Last Year';
                } else {
                    updateUnauthorizedChart(dates, unauthorizedCounts);
                    document.querySelectorAll('.chart-title h3')[1].textContent = 'Unauthorized Vehicles Over the Last Week';
                }
            }

            document.getElementById('timeRange').addEventListener('change', handleViolationTimeRangeChange);
            document.getElementById('timeRangeUnauthorized').addEventListener('change', handleUnauthorizedTimeRangeChange);

            // Initialize with the default time range
            handleViolationTimeRangeChange();
            handleUnauthorizedTimeRangeChange();

            async function exportChart(chart, chartType) {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Capture the chart as an image
                const canvas = chart.canvas;
                const imgData = canvas.toDataURL('image/png');

                // Set font size
                doc.setFontSize(14);

                // Determine the chart title and content based on the chart type
                const timeRange = document.getElementById(chartType === 'violation' ? 'timeRange' : 'timeRangeUnauthorized').value;
                const contentDescription = timeRange === 'month' ? 'Month Chart' : 'Week Chart';
                const title = chartType === 'violation' ? 'Violation Reports Chart' : 'Unauthorized Vehicles Chart';

                // Add title, chart content, and date/time to the PDF
                doc.text(title, 10, 10);
                doc.text(`Chart Content: ${contentDescription}`, 10, 20);
                doc.text(`Date: ${new Date().toLocaleString()}`, 10, 30);

                // Add chart image to the PDF
                doc.addImage(imgData, 'PNG', 10, 40, 190, 100);

                // Generate filename with chart type, content, and date
                const fileName = `${chartType === 'violation' ? 'Violation' : 'Unauthorized'}-${contentDescription}-${new Date().toISOString().split('T')[0]}.pdf`;
                doc.save(fileName);
            }

            document.getElementById('chart-violation-export').addEventListener('click', () => {
                exportChart(violationChart, 'violation');
            });

            document.getElementById('chart-unauthorized-export').addEventListener('click', () => {
                exportChart(unauthorizedChart, 'unauthorized');
            });
        </script>
    </main><!-- End #main -->

    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>

    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
