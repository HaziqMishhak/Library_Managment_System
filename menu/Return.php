<?php
include('session.php');
include('username.php');
include('header.php');
include('dbconnect.php');
include('sidebar.php');
include('../Action/StatusUpdate.php');
?>

<!-- ======= HTML ================================================================ -->

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Return Book list</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="Return.php">Home</a></li>
        <li class="breadcrumb-item active">This is Return Book list</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->


<!-- Start table Content -->
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Student Return Book Detail</h5>
              <p>Referesh Page for latest Update</p>
              

              <label for="statusFilter">Filter by Status:</label>
              <select id="statusFilter" name="status">
                  <option value="all">All</option>
                  <option value="Late">Late</option>
                  <option value="On-Time">On-Time</option>
              </select>

              <!-- Table with stripped rows -->
              <table class="table datatable" id="transactionsTable">
                <thead>
                  <tr>
                    <th scope="col">StudentID</th>
                    <th scope="col">Book Name</th>
                    <th scope="col">Borrow Time</th>
                    <th scope="col">Return Time</th>
                    <th scope="col">Return Status</th>
                    <th scope="col">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- table to db  -->
<?php
                $sql = "SELECT ReturnBook.ReturnID, Students.StudentID, Books.BookName, BorrowBook.BorrowDateTime, ReturnBook.ReturnDate, ReturnBook.Status
                FROM ReturnBook
                JOIN BorrowBook ON ReturnBook.BorrowID = BorrowBook.BorrowID
                JOIN Students ON BorrowBook.StudentID = Students.StudentID
                JOIN Books ON BorrowBook.BookID = Books.BookID";

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['StudentID']."</td>";
                echo "<td>".$row['BookName']."</td>"; 
                echo "<td>".$row['BorrowDateTime']."</td>";
                echo "<td>".$row['ReturnDate']."</td>";
                echo "<td>".$row['Status']."</td>";
                echo "<td><button class='btn btn-danger' onclick='confirmDelete(".$row['ReturnID'].")'>Delete</button></td>";
                echo "</tr>";
                }
                } else {
                echo "<tr><td colspan='6'>No results found</td></tr>";
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
function confirmDelete(returnID) {
    if (confirm("Are you sure you want to delete this record?")) {
        let formData = new FormData();
        formData.append('action', 'delete');
        formData.append('returnID', returnID);
        fetch('../Action/ReturnAct.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
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
</script>



<script src="../assets/javascript/filter.js"></script>

</body>
</html>
