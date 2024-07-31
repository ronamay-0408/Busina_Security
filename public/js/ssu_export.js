function exportTableToCSV() {
    var csv = [];
    var rows = document.querySelectorAll("#ssuTable tr");
    
    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (var j = 0; j < cols.length; j++) {
            row.push(cols[j].innerText.replace(/,/g, '')); // Replace commas in data to avoid breaking CSV format
        }
        
        csv.push(row.join(","));
    }

    // Create a CSV blob and generate a link to download it
    var csvFile;
    var downloadLink;
    var date = new Date();
    var formattedDate = date.toISOString().split('T')[0]; // Format date as YYYY-MM-DD
    var filename = `ssu_personnels_${formattedDate}.csv`; // Construct filename

    csvFile = new Blob([csv.join("\n")], { type: "text/csv" });

    downloadLink = document.createElement("a");

    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);

    downloadLink.click();
}