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


<main id="main" class="main">
  <div class="pagetitle">
    <h1>Payment Transaction</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="Payment.php">Home</a></li>
        <li class="breadcrumb-item active">This is Payment Transaction list</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->


<!-- Start table Content -->
<section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Payment Transactions</h5>
              <input type="text" id="PaymentInput" placeholder="Search..">
              <br>

              <!-- Table with stripped rows -->
              <table class="table table-striped" id="PaymentList">
              <br>
                <thead>
                  <tr>
                    <th scope="col">Transaction ID</th>
                    <th scope="col">Student ID</th>
                    <th scope="col">Total Amount Paid</th>
                    <th scope="col">Number of Books</th>
                    <th scope="col">Receipt</th>
                    <th scope="col">Payment Date</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- table to db  -->
<?php
$sql = "SELECT pt.TransactionID, pt.StudentID, pt.TotalAmount, pt.NumberOfBooks, pt.ReceiptImagePath, pt.PaymentDate 
FROM PaymentTransaction pt
ORDER BY pt.PaymentDate DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>".$row['TransactionID']."</td>";
        echo "<td>".$row['StudentID']."</td>"; 
        echo "<td>".$row['TotalAmount']."</td>";
        echo "<td>".$row['NumberOfBooks']."</td>";
        echo "<td><a href='".$row['ReceiptImagePath']."' target='_blank'>View Receipt</a></td>";
        echo "<td>".$row['PaymentDate']."</td>";
        echo "</tr>";
    }
} else {
  echo "<tr><td colspan='6'>No payment transactions found</td></tr>";
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
function confirmDelete(borrowID) {
    var result = confirm("Are you really want to delete this data?");
    if (result) {
        window.location.href="../Action/DeleteBorrow.php?BorrowID=" + borrowID;
    }
}
</script>
<script>
    document.getElementById("PaymentInput").addEventListener("keyup", function() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("PaymentInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("PaymentList");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0]; // Column to be filtered (Transaction ID in this case)
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    });
</script>
<script src="../assets/javascript/Payment.js"></script>
</body>
</html>
