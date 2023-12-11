document.getElementById('statusFilter').addEventListener('change', function() {
    filterStatus();  // Call function
});

function filterStatus() {
    //selected status 
    let selectedStatus = document.getElementById('statusFilter').value;

    //all table rows
    let tableRows = document.querySelectorAll('#transactionsTable tbody tr');

    // Loop through each table row
    tableRows.forEach(function(row) {
        // Get the status from the 5th column (index 4)
        let rowStatus = row.cells[4].innerText;
        console.log(rowStatus);

        if (selectedStatus === 'all') {
            row.style.display = '';  // show all rows if "all" is selected
        } else if (rowStatus.toLowerCase() === selectedStatus.toLowerCase()) {
            row.style.display = '';  // show rows that match the selected status
        } else {
            row.style.display = 'none';  // hide other rows
        }
    });
}


