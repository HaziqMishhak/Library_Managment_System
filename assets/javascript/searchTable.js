document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  const rows = document.getElementById('transactionsTable').getElementsByTagName('tr');

  function searchTable() {
    const query = searchInput.value.toLowerCase();

    for (let i = 1; i < rows.length; i++) { // Start from 1 to skip the header row
      const row = rows[i];
      // Note that the indexing should match the order of your columns
      const transactionIdCell = row.getElementsByTagName('td')[0].textContent.toLowerCase();
      const studentIdCell = row.getElementsByTagName('td')[1].textContent.toLowerCase();
      const bookNameCell = row.getElementsByTagName('td')[2].textContent.toLowerCase();

      // Check if the transaction ID, student ID, or book name cell text includes the query
      let match = transactionIdCell.includes(query) || 
                  studentIdCell.includes(query) || 
                  bookNameCell.includes(query);
  
      // If there is a match, show the row, otherwise hide it
      row.style.display = match ? '' : 'none';
    }
  }

  searchInput.addEventListener('keyup', searchTable);
});
