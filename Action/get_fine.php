<?php
// Database connection include
include 'dbconnect.php';

// Assuming you're sending the StudentID as a POST request
$studentId = isset($_POST['studentId']) ? $_POST['studentId'] : '';

// Prepare the SQL statement
$stmt = $conn->prepare("
    SELECT 
        s.StudentID,
        b.BookName,
        rb.ReturnDate,
        f.AmountDue,
        f.PaymentStatus
    FROM 
        Fine f
    JOIN 
        ReturnBook rb ON f.ReturnID = rb.ReturnID
    JOIN 
        BorrowBook bb ON rb.BorrowID = bb.BorrowID
    JOIN 
        Books b ON bb.BookID = b.BookID
    JOIN 
        Students s ON bb.StudentID = s.StudentID
    WHERE 
        s.StudentID = ? AND f.PaymentStatus = 'Pending'
    ORDER BY 
        rb.ReturnDate DESC;
");

// Bind the StudentID to the prepared statement
$stmt->bind_param("s", $studentId);

// Execute the query
$stmt->execute();

// Fetch the results
$result = $stmt->get_result();
$fines = $result->fetch_all(MYSQLI_ASSOC);

// Close the statement
$stmt->close();

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($fines);
?>
