$(function() {
    // Initialize datepickers
    $("#year-filter").datepicker({
        changeMonth: false,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'yy',
        onClose: function(dateText, inst) { 
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, 1));
            filterTable();
        }
    }).focus(function () {
        $(".ui-datepicker-month").hide();
        $(".ui-datepicker-calendar").hide();
    });

    $("#month-filter").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'MM yy',
        onClose: function(dateText, inst) { 
            var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            $(this).datepicker('setDate', new Date(year, month, 1));
            filterTable();
        }
    }).focus(function () {
        $(".ui-datepicker-calendar").hide();
    });

    $("#day-filter").datepicker({
        changeMonth: true,
        changeYear: true,
        showButtonPanel: true,
        dateFormat: 'DD, d MM, yy',
        onClose: function(dateText, inst) { 
            filterTable();
        }
    });

    // Clear buttons
    $("#clear-year").on("click", function() {
        $("#year-filter").val('');
        filterTable();
    });

    $("#clear-month").on("click", function() {
        $("#month-filter").val('');
        filterTable();
    });

    $("#clear-day").on("click", function() {
        $("#day-filter").val('');
        filterTable();
    });

    // Filtering function
    function filterTable() {
        var yearFilter = $("#year-filter").val();
        var monthFilter = $("#month-filter").val();
        var dayFilter = $("#day-filter").val();

        $("#violationTable tbody tr").each(function() {
            var rowDateText = $(this).find("td:nth-child(1)").text(); // Date is in the first column
            var rowDate = new Date(rowDateText);

            var showRow = true;

            if (yearFilter) {
                var rowYear = rowDate.getFullYear();
                var filterYear = parseInt(yearFilter, 10);
                if (rowYear !== filterYear) {
                    showRow = false;
                }
            }

            if (monthFilter) {
                var rowMonth = rowDate.getMonth(); // 0-based index
                var filterMonth = new Date(monthFilter + ' 1').getMonth();
                if (rowMonth !== filterMonth) {
                    showRow = false;
                }
            }

            if (dayFilter) {
                var rowDay = rowDate.getDate();
                var filterDay = new Date(dayFilter).getDate();
                if (rowDay !== filterDay) {
                    showRow = false;
                }
            }

            $(this).toggle(showRow);
        });
    }

    // Export to CSV function
    function exportTableToCSV(filename) {
        var csv = [];
        var rows = $("#violationTable tbody tr:visible"); // Only visible rows

        rows.each(function() {
            var rowData = [];
            var cols = $(this).find("td");

            cols.each(function(colIndex) {
                let cellText = $(this).text();
                // If cell contains multiple lines, replace new lines with space
                cellText = cellText.replace(/\n/g, ' ').trim();

                // Handle the last column as "Proof Image" (could be omitted)
                if (colIndex === cols.length - 1) {
                    rowData.push(`"${cellText}"`);
                } else {
                    rowData.push(cellText);
                }
            });

            // Only add rows that are not empty or invalid
            if (rowData.length > 0) {
                csv.push(rowData.join(","));
            }
        });

        // Create CSV file and trigger download
        var csvFile = new Blob([csv.join("\n")], { type: "text/csv" });
        var downloadLink = document.createElement("a");

        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }

    // Bind the export button click event
    $(".export-child").on("click", function() {
        var today = new Date();
        var dateStr = today.getFullYear() + '-' +
            ('0' + (today.getMonth() + 1)).slice(-2) + '-' +
            ('0' + today.getDate()).slice(-2);
        var filename = 'violations_' + dateStr + '.csv';
        
        exportTableToCSV(filename);
    });
});
