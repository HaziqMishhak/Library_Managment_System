<?php

include('session.php');
include('username.php');
include('header.php');
include('dbconnect.php');
include('sidebar.php');
include('../Action/StatusUpdate.php');
?>

<!-- ======= HTML ================================================================ -->
<!-- modal Update -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-labelledby="editBookModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBookModalLabel">Edit Book Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="editBookForm">
          <div class="mb-3">
            <label for="editBookID" class="form-label">Book ID</label>
            <input type="text" class="form-control" id="editBookID" name="bookID" readonly>
          </div>
          <div class="mb-3">
            <label for="editBookName" class="form-label">Book Name</label>
            <input type="text" class="form-control" id="editBookName" name="bookName">
          </div>
          <div class="mb-3">
            <label for="editAuthorName" class="form-label">Author Name</label>
            <input type="text" class="form-control" id="editAuthorName" name="authorName">
          </div>
          <div class="mb-3">
            <label for="editPublishDate" class="form-label">Publish Date</label>
            <input type="date" class="form-control" id="editPublishDate" name="publishDate">
          </div>
          <div class="mb-3">
            <label for="editBookStatus" class="form-label">Book Status</label>
            <select class="form-select" id="editBookStatus" name="bookStatus">
              <option value="Available">Available</option>
              <option value="Unavailable">Unavailable</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="handleUpdate()">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- ... -->
<main id="main" class="main">
  <div class="pagetitle">
    <h1>Book List</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="Book.php">Home</a></li>
        <li class="breadcrumb-item active">This is Book list</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->


<!-- Start table Content -->
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Book Details</h5>
              <p>Please Referesh the Web for Real Time Data</p>


              <label for="statusFilter">Filter by Status:</label>
                <select id="statusFilter" name="status">
                    <option value="all">All</option>
                    <option value="Available">Available</option>
                    <option value="Unavailable">Unavailable</option>
                </select>

              <!-- Table with stripped rows -->
              <table class="table datatable" id="transactionsTable">
                <thead>
                  <tr>
                    <th scope="col">BookID</th>
                    <th scope="col">Book Name</th>
                    <th scope="col">Author Name</th>
                    <th scope="col">Publish Date</th>
                    <th scope="col">Book Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- table to db  -->
                  <?php
                  $sql = "SELECT Books.BookID, Books.BookName, Books.AuthorName, Books.PublishDate, Books.IsActive,
                  CASE
                      WHEN LatestBorrow.Status IS NULL OR LatestBorrow.Status = 'Returned' THEN 'Available'
                      WHEN LatestBorrow.Status = 'Late' OR LatestBorrow.Status = 'Not Due' THEN 'Unavailable'
                      ELSE 'Unavailable'
                  END AS BookStatus
                  FROM Books
                  LEFT JOIN (
                      SELECT BorrowBook.BookID, BorrowBook.Status
                      FROM BorrowBook
                      JOIN (
                          SELECT BookID, MAX(BorrowDateTime) as LatestDate
                          FROM BorrowBook
                          GROUP BY BookID
                      ) AS LatestBorrow ON BorrowBook.BookID = LatestBorrow.BookID AND BorrowBook.BorrowDateTime = LatestBorrow.LatestDate
                  ) AS LatestBorrow ON Books.BookID = LatestBorrow.BookID
                  WHERE Books.IsActive = TRUE"; // Only select active books
          
                  $result = $conn->query($sql);
                  
                  if ($result->num_rows > 0) {
                      // Output data of each row
                      while($row = $result->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>".$row['BookID']."</td>";
                          echo "<td>".$row['BookName']."</td>"; 
                          echo "<td>".$row['AuthorName']."</td>";
                          echo "<td>".$row['PublishDate']."</td>";
                          echo "<td>".$row['BookStatus']."</td>"; // Updated to show BookStatus from the CASE statement
                          echo "<td><button type='button' class='btn btn-primary' onclick='openEditModal(" .
                                "\"" . $row['BookID'] . "\", " .
                                "\"" . htmlspecialchars($row['BookName'], ENT_QUOTES) . "\", " .
                                "\"" . $row['AuthorName'] . "\", " .
                                "\"" . $row['PublishDate'] . "\", " .
                                "\"" . $row['BookStatus'] . "\", " .
                                ")'>Edit</button> ";
                          echo "<button class='btn btn-danger' onclick='confirmDelete(".$row['BookID'].")'>Delete</button></td>";
                          echo "</tr>";
                      }
                  } else {
                      echo "<tr><td colspan='6'>No books found</td></tr>";
                  }
                  
                  // Close connection
                  $conn->close();
                  ?><!-- End table to db  -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

<?php
include('footer.php');
?>

<script>
function confirmDelete(bookID) {
    if (confirm("Are you sure you want to delete this record?")) {
        // Create a FormData object and append the borrowID to it
        let formData = new FormData();
        formData.append('action', 'delete'); // Add an action field to indicate this is a delete operation
        formData.append('bookID', bookID);

        // Send a POST request with the FormData
        fetch('../Action/BookAct.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                window.location.reload(); // Reload the page to reflect the deletion
            } else {
                alert(data.message); // Show the error message
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
    }
}

function openEditModal(bookID, bookName, authorName, publishDate, bookStatus) {
    // Populate the form fields in the modal
    document.getElementById('editBookID').value = bookID;
    document.getElementById('editBookName').value = bookName;
    document.getElementById('editAuthorName').value = authorName;
    document.getElementById('editPublishDate').value = publishDate; // Make sure the date format is 'YYYY-MM-DD'
    document.getElementById('editBookStatus').value = bookStatus === 'Available' ? 'Available' : 'Unavailable';

    $('#editBookModal').modal('show');
}

// Function to handle the update action
function handleUpdate() {
    // Confirm before updating
    if (confirm("Are you sure you want to save these changes?")) {
        var formData = new FormData(document.getElementById('editBookForm'));

        fetch('../Action/BookAct.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            alert('An error occurred: ' + error.message);
        });
    }
}

</script>
<script src="../assets/javascript/filter.js"></script>

</body>
</html>