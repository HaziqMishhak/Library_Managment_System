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
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Borrow</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Edit form fields -->
        <form id="editForm">
          <input type="hidden" id="borrowID" name="borrowID">
          <div class="mb-3">
            <label for="studentID" class="form-label">Student ID</label>
            <input type="text" class="form-control" id="studentID" name="studentID" readonly>
          </div>
          <div class="mb-3">
            <label for="bookName" class="form-label">Book Name</label>
            <input type="text" class="form-control" id="bookName" name="bookName" readonly>
          </div>
          <div class="mb-3">
            <label for="borrowDateTime" class="form-label">Borrow Time</label>
            <input type="datetime-local" class="form-control" id="borrowDateTime" name="borrowDateTime">
          </div>
          <div class="mb-3">
            <label for="dueTime" class="form-label">Due Time</label>
            <input type="datetime-local" class="form-control" id="dueTime" name="dueTime">
          </div>
        </form>
        <!-- End Edit form fields -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateBorrow()">Save changes</button>
      </div>
    </div>
  </div>
</div>
<!-- ... -->




<main id="main" class="main">
  <div class="pagetitle">
    <h1>Borrow</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="Borrow.php">Home</a></li>
        <li class="breadcrumb-item active">This is Borrow Book list</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->


<!-- Start table Content -->
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Student Borrow Book Detail</h5>
              <p>Referesh Page for latest Update</p>
              

              <label for="statusFilter">Filter by Status:</label>
              <select id="statusFilter" name="status">
                  <option value="all">All</option>
                  <option value="Late">Late</option>
                  <option value="Not Due">Not Due</option>
                  <option value="Returned">Returned</option>
              </select>

              <!-- Table with stripped rows -->
              <table class="table datatable" id="transactionsTable">
                <thead>
                  <tr>
                    <th scope="col">StudentID</th>
                    <th scope="col">Book Name</th>
                    <th scope="col">Borrow Time</th>
                    <th scope="col">Due Time</th>
                    <th scope="col">Borrow Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- table to db  -->
                <?php
                $sql = "SELECT BorrowBook.BorrowID, BorrowBook.StudentID, Books.BookName, BorrowBook.BorrowDateTime, 
                        BorrowBook.DueTime, BorrowBook.Status 
                        FROM BorrowBook 
                        JOIN Books ON BorrowBook.BookID = Books.BookID";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                  while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['StudentID'] . "</td>";
                    echo "<td>" . $row['BookName'] . "</td>";
                    echo "<td>" . $row['BorrowDateTime'] . "</td>";
                    echo "<td>" . $row['DueTime'] . "</td>";
                    echo "<td>" . $row['Status'] . "</td>";
                    echo "<td><button type='button' class='btn btn-primary' onclick='openEditModal(" .
                        "\"" . $row['BorrowID'] . "\", " .
                        "\"" . $row['StudentID'] . "\", " .
                        "\"" . htmlspecialchars($row['BookName'], ENT_QUOTES) . "\", " .
                        "\"" . $row['BorrowDateTime'] . "\", " .
                        "\"" . $row['DueTime'] . "\", " .
                        ")'>Edit</button> ";
                    echo "<button class='btn btn-danger' onclick='confirmDelete(".$row['BorrowID'].")'>Delete</button></td>";
                    echo "</tr>";
                    }
                }
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
function confirmDelete(borrowID) {
    if (confirm("Are you sure you want to delete this record?")) {
        // Create a FormData object and append the borrowID to it
        let formData = new FormData();
        formData.append('action', 'delete'); // Add an action field to indicate this is a delete operation
        formData.append('BorrowID', borrowID);

        // Send a POST request with the FormData
        fetch('../Action/BorrowAct.php', {
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


function updateBorrow() {
    // Confirm before updating
    if (confirm("Are you sure you want to save these changes?")) {
        var formData = new FormData(document.getElementById('editForm'));

        fetch('../Action/BorrowAct.php', {
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
function openEditModal(borrowID, studentID, bookName, borrowDateTime, dueTime) {
    // Populate the form fields
    document.getElementById('borrowID').value = borrowID;
    document.getElementById('studentID').value = studentID;
    document.getElementById('bookName').value = bookName;
    document.getElementById('borrowDateTime').value = borrowDateTime.slice(0, 16); // Slice to match the datetime-local format
    document.getElementById('dueTime').value = dueTime.slice(0, 16); // Slice to match the datetime-local format

    // Show the modal
    $('#editModal').modal('show');
}

</script>




<script src="../assets/javascript/filter.js"></script>

</body>
</html>
