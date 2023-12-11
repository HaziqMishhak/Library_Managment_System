<?php

include('session.php');
include('username.php');
include('header.php');
include('dbconnect.php');
include('sidebar.php');
include('../Action/StatusUpdate.php');
?>

<!-- ======= HTML ================================================================ -->
<!DOCTYPE html>
<html>
<head>
    <title>Menu</title>
</head>
<body>

<main id="main" class="main">
  <div class="pagetitle">
    <h1>Add Book</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="Add.php">Home</a></li>
        <li class="breadcrumb-item active">This is Add book form</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->


<!-- Start table Content -->
    <section class="section">
        <div class="row">
            <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                <h5 class="card-title">Add New Book</h5>
                <p>Please Referesh the Web for Real Time Data</p>


                <!-- Add book -->

        <!-- Manual Entry Form -->
        <form action="../Action/Book_handle.php" method="post">
            <div class="form-group">
                <label for="BookID">Book RFID:</label>
                <input type="text" class="form-control" id="bookID" name="BookID" required>
            </div>
            <div class="form-group">
                <label for="bookName">Book Name:</label>
                <input type="text" class="form-control" id="bookName" name="bookName" required>
            </div>
            <div class="form-group">
                <label for="authorName">Author Name:</label>
                <input type="text" class="form-control" id="authorName" name="authorName" required>
            </div>
            <div class="form-group">
                <label for="publishDate">Publish Date:</label>
                <input type="date" class="form-control" id="publishDate" name="publishDate">
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Add Book</button>
        </form>    
        <br>    
        <!-- Separate CSV Upload Form -->
        <form action="../Action/Book_handle.php" method="post" enctype="multipart/form-data">
            <label style="margin-left: 10px;">Please upload CSV file format</label>
            <input type="file" name="file" accept=".csv" style="display:inline;">
            <button type="submit" class="btn btn-secondary" style="margin-left: 10px;">Upload</button>
        </form>

        <br>
        <!-- Download CSV Template -->
        <a href="../assets/AddbookTemp.xlsx" download class="btn btn-info" style="margin-left: 10px;">Download Excel Template</a>

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

<script src="../assets/javascript/filter.js"></script>

</body>
</html>
