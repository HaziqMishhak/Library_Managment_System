<?php
//NOT DONE ANYTHING YET
include('session.php');
include('username.php');
include('header.php');
include('dbconnect.php');
include('sidebar.php');
include('../Action/StatusUpdate.php');
?>

<!-- ======= HTML ================================================================ -->
<!-- Modal -->
<div class="modal fade" id="insertdata" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="insertdataLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="insertdataLabel">Fine Payment</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="paymentForm" method="POST" enctype="multipart/form-data">
        <div class="modal-body">
          <!-- Search Bar -->
          <input type="text" class="form-control mb-3" id="studentIdSearch" name="studentId" placeholder="Enter Student ID" aria-label="Student ID">
          <button type="button" id="searchBtn" class="btn btn-primary mb-3">Search</button>

          <!-- Results Table -->
          <table class="table">
            <thead>
              <tr>
                <th scope="col">StudentID</th>
                <th scope="col">BookName</th>
                <th scope="col">ReturnDate</th>
                <th scope="col">AmountDue</th>
              </tr>
            </thead>
            <tbody id="searchResults">
              <!-- Search results populate here -->
            </tbody>
          </table>

          <!-- Total Amount Due -->
          <h5>Total: <span id="totalAmountDue">0.00</span></h5>

          <!-- Image Upload -->
          <div class="mb-3">
            <label for="receiptImage" class="form-label">Upload Receipt:</label>
            <input class="form-control" type="file" id="receiptImage" name="receiptImage" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" name="submitPayment">Submit Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- End Modal -->




<main id="main" class="main">
  <div class="pagetitle">
    <h1>Student Fine list</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="Fine.php">Home</a></li>
        <li class="breadcrumb-item active">This is Return fine list</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->


<!-- Start table Content -->
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Student Fine Detail</h5>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insertdata">
                Fine Payment
              </button>
              <br>
              <br>
              <label for="statusFilter">Filter by Status:</label>
              <select id="statusFilter" name="PaymentStatus">
                  <option value="all">All</option>
                  <option value="Paid">Paid</option>
                  <option value="Pending">Pending</option>
              </select>
<br>
<br>
              <input type="text" id="searchInput" placeholder="Search..">
<br>
<br>
              <!-- Table with stripped rows -->
              <table class="table table-striped" id="transactionsTable">
                <thead>
                  <tr>
                  <th scope="col">Transaction ID</th>
                    <th scope="col">StudentID</th>
                    <th scope="col">Book Name</th>
                    <th scope="col">Fine Amount</th>
                    <th scope="col">Payment Status</th>
                    <th scope="col">Borrow Time</th>
                    <th scope="col">Return Time</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- table to db  -->
                  <?php
                  $sql = "SELECT Students.StudentID, Books.BookName, BorrowBook.BorrowDateTime, ReturnBook.ReturnDate, 
                          Fine.AmountDue, Fine.PaymentStatus, Fine.TransactionID, BorrowBook.BorrowID
                          FROM Fine
                          JOIN ReturnBook ON Fine.ReturnID = ReturnBook.ReturnID
                          JOIN BorrowBook ON ReturnBook.BorrowID = BorrowBook.BorrowID
                          JOIN Students ON BorrowBook.StudentID = Students.StudentID
                          JOIN Books ON BorrowBook.BookID = Books.BookID";

                  $result = $conn->query($sql);

                  if ($result->num_rows > 0) {
                      while($row = $result->fetch_assoc()) {
                          echo "<tr>";
                          echo "<td>".$row['TransactionID']."</td>";
                          echo "<td>".$row['StudentID']."</td>";
                          echo "<td>".$row['BookName']."</td>"; 
                          echo "<td>".$row['AmountDue']."</td>";
                          echo "<td>".$row['PaymentStatus']."</td>";
                          echo "<td>".$row['BorrowDateTime']."</td>";
                          echo "<td>".$row['ReturnDate']."</td>";
                          echo "</tr>";
                      }
                  } else {
                      echo "<tr><td colspan='8'>No outstanding fines found</td></tr>";
                  }
                  $conn->close();
                  ?>
                  <!-- End table to db  -->
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
    // Function to hide the modal
    function closeMyModal() {
        var myModal = new bootstrap.Modal(document.getElementById('myModal'));
        myModal.hide();
    }
  </script>

<script src="../assets/javascript/filter.js"></script>
<script src="../assets/javascript/searchTable.js"></script>
<script src="../assets/javascript/Payment.js"></script>
</body>
</html>
