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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script type="text/javascript">
        function preventBack(){window.history.forward()};
        setTimeout("preventBack()",0);
        window.onunload=function(){null;}
    </script>
</head>

<body>
    @include('SSUHead.partials.sidebar')

    <main id="main" class="main">
        <div class="datetime-btn">
            <div class="burger-btn"><i class="bi bi-list toggle-sidebar-btn"></i> <!-- Moved toggle button here --></div>
            <div class="date-time"></div>
        </div>

        <div class="banner">
            <img src="images/BUTorch.png">
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
                        <p>Today's Vehicle Owner Logs</p>
                        <h2>{{ $totalVehicleOwnerLogsToday }}</h2>
                    </div>
                    <hr class="dark horizontal my-0">
                    <div class="count-box">
                        <p>
                            <span class="{{ $vehicleOwnerLogsDifference >= 0 ? 'increase' : 'decrease' }}">{{ $vehicleOwnerLogsDifference >= 0 ? '+' : '-' }}{{ abs($vehicleOwnerLogsDifference) }}</span>
                            Than Yesterday
                        </p>
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
                                <h3>Violation Reports Over the Last Year</h3>
                            </div>
                            <div class="chart-option">
                                <select id="timeRange" class="chart-dropdown">
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-export" id="chart-violation-print">
                            <i class="bi bi-printer"></i>
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
                                <h3>Unauthorized Vehicles Over the Last Year</h3>
                            </div>
                            <div class="chart-option">
                                <select id="timeRangeUnauthorized" class="chart-dropdown">
                                    <option value="week">Week</option>
                                    <option value="month">Month</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-export" id="chart-unauthorized-print">
                            <i class="bi bi-printer"></i>
                        </div>
                    </div>
                    <canvas id="unauthorizedChart"></canvas>
                </div>
            </div>
        </div>

        <div class="pop-violations">
            <div class="pop-title" style="display: flex; justify-content: space-between; align-items: center;">
                <h3>Violation Rate for the Month of {{ $currentMonthName }} {{ $currentYear }}</h3>
                <button id="rate-print-button" class="btn btn-primary" style="margin-left: 10px;">
                    <i class="bi bi-printer"></i>
                </button>
            </div>
            <div class="violations-list">
                <table id="pdf-content">
                    <thead>
                        <tr>
                            <th>Violation Name</th>
                            <th>Violation Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($violations as $violation)
                            <tr>
                                <td>{{ $violation->violationType->violation_name ?? 'Unknown Violation' }}</td>
                                <td>{{ $violation->count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main><!-- End #main -->
    <!-- Pass PHP data to JavaScript -->
    <script>
        window.chartData = {
            dates: <?php echo json_encode($dates); ?>,
            violationCounts: <?php echo json_encode($violationCounts); ?>,
            unauthorizedCounts: <?php echo json_encode($unauthorizedCounts); ?>,
            months: <?php echo json_encode($months); ?>,
            monthlyViolationCounts: <?php echo json_encode($monthlyViolationCounts); ?>,
            monthlyUnauthorizedCounts: <?php echo json_encode($monthlyUnauthorizedCounts); ?>
        };
    </script>

    <!-- Add these scripts before the closing </body> tag -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.7.0/chart.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        const { dates, violationCounts, unauthorizedCounts, months, monthlyViolationCounts, monthlyUnauthorizedCounts } = window.chartData;

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

        function updateChart(chart, labels, data, chartType) {
            chart.data.labels = labels;
            chart.data.datasets[0].data = data;
            chart.update();
        }

        function handleTimeRangeChange() {
            const timeRange = document.getElementById('timeRange').value;
            const timeRangeUnauthorized = document.getElementById('timeRangeUnauthorized').value;

            if (timeRange === 'month') {
                updateChart(violationChart, months, monthlyViolationCounts, 'violation');
                document.querySelector('.chart-title h3').textContent = 'Violation Reports Over the Last Year';
            } else {
                updateChart(violationChart, dates, violationCounts, 'violation');
                document.querySelector('.chart-title h3').textContent = 'Violation Reports Over the Last Week';
            }

            if (timeRangeUnauthorized === 'month') {
                updateChart(unauthorizedChart, months, monthlyUnauthorizedCounts, 'unauthorized');
                document.querySelectorAll('.chart-title h3')[1].textContent = 'Unauthorized Vehicles Over the Last Year';
            } else {
                updateChart(unauthorizedChart, dates, unauthorizedCounts, 'unauthorized');
                document.querySelectorAll('.chart-title h3')[1].textContent = 'Unauthorized Vehicles Over the Last Week';
            }
        }

        document.getElementById('timeRange').addEventListener('change', handleTimeRangeChange);
        document.getElementById('timeRangeUnauthorized').addEventListener('change', handleTimeRangeChange);

        // Initialize with the default time range
        handleTimeRangeChange();
    </script>

    <script>
        // Event listener for chart print buttons
        document.getElementById('chart-violation-print').addEventListener('click', function() {
            printReport('violationChart', 'Violation Report', 'chart');
        });

        document.getElementById('chart-unauthorized-print').addEventListener('click', function() {
            printReport('unauthorizedChart', 'Unauthorized Vehicles Report', 'chart');
        });

        // Event listener for table print button
        document.getElementById('rate-print-button').addEventListener('click', function() {
            printReport('pdf-content', 'Popular Violation Report', 'table');
        });

        // Generalized print function for charts and tables
        function printReport(elementId, reportTitle, type) {
            // Get the content for the report (chart image or table HTML)
            let content;
            if (type === 'chart') {
                const canvas = document.getElementById(elementId);
                content = canvas.toDataURL('image/png');
            } else if (type === 'table') {
                content = document.getElementById(elementId).outerHTML;
            }

            // Get the current date and time for printing
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            const formattedTime = currentDate.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

            // Create the print window
            const printWindow = window.open('', '_blank');
            printWindow.document.open();
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print ${reportTitle}</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                margin: 20px;
                            }
                            @media print {
                                @page {
                                    margin: 10px 20px 20px 20px;
                                }
                                .no-print {
                                    display: none;
                                }
                                .print-header {
                                    text-align: center;
                                    margin-bottom: 20px;
                                }
                                .print-header h1 {
                                    margin: 0;
                                    font-size: 20px;
                                    font-weight: bold;
                                }
                                .print-header h2 {
                                    margin: 0;
                                    font-size: 16px;
                                    font-weight: normal;
                                }
                                .print-header h3 {
                                    margin: 0;
                                    font-size: 16px;
                                    font-weight: bold;
                                    text-decoration: underline;
                                    color: black;
                                }
                                .chart-title {
                                    text-align: center;
                                }
                                .chart-image {
                                    display: block;
                                    margin: 20px auto;
                                }
                                table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    margin-top: 20px;
                                }
                                th, td {
                                    padding: 10px;
                                    border: 1px solid #ddd;
                                    text-align: left;
                                }
                                th {
                                    background-color: #f4f4f4;
                                }
                                .date-time {
                                    text-align: center;
                                    margin: 10px 0;
                                    font-family: Arial, sans-serif;
                                    font-size: 14px;
                                    line-height: 1.5;
                                }
                                .date-time p {
                                    margin: 0;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        <!-- Header -->
                        <div class="print-header">
                            <h1>Bicol University</h1>
                            <h2>Rizal St., Legazpi City, Albay</h2>
                            <h3>BU Head SSU Section</h3>
                        </div>

                        <!-- Report Title -->
                        <div class="chart-title">
                            <h3>${reportTitle}</h3>
                        </div>
                        
                        <!-- Content -->
                        ${type === 'chart' ? `<img src="${content}" alt="${reportTitle}" class="chart-image">` : content}

                        <!-- Date and Time -->
                        <div class="date-time">
                            <p><b>Printed By:</b> {{ Session::get('user')['fname'] }} {{ Session::get('user')['lname'] }}</p>
                            <p><b>Date:</b> ${formattedDate} at ${formattedTime}</p>
                        </div>
                    </body>
                </html>
            `);
            printWindow.document.close();

            // Trigger print dialog and close the window afterward
            printWindow.onload = function() {
                printWindow.print();
                printWindow.close();
            };
        }
    </script>
    @include('SSUHead.partials.footer')
    <!-- Template Main JS File // NAVBAR // -->
    <script src="{{ asset('js/navbar.js') }}"></script>
    <!-- DATE AND TIME -->
    <script src="{{ asset('js/date_time.js') }}"></script>
</body>
</html>
