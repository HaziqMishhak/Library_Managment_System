<?php

include 'dbconnect.php';
include 'statusUpdateFunction.php';

// Update the borrow book status before executing the search
updateBorrowStatus($conn);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    
    // Get the search term (StudentID)
    $searchTerm = $_GET['search'];

    // Check if input is empty or not a valid StudentID format 
    if (empty($searchTerm) || !preg_match("/^[\w\-]{1,10}$/", $searchTerm)) {
        echo "<script>alert('Please provide a valid StudentID!');</script>";
        return;
    }

    // Prepare and execute the SQL query
    $sql = "SELECT BorrowBook.StudentID, Books.BookName, BorrowBook.BorrowDateTime, 
        BorrowBook.DueTime, BorrowBook.Status 
        FROM BorrowBook 
        JOIN Books ON BorrowBook.BookID = Books.BookID 
        WHERE BorrowBook.StudentID LIKE ?";

    $stmt = $conn->prepare($sql);
    $searchTerm = "%" . $_GET['search'] . "%";  // Add wildcard % for partial matches
    $stmt->bind_param("s", $searchTerm);
    $stmt->execute();

    // Get the results
    $result = $stmt->get_result();

    // Check if any results were found
    if ($result->num_rows > 0) {
        // Display the results as an HTML table
        echo "<div style='height: 30px;'></div>"; //for the gap between the search bar and the table
        echo "<h2>Search Results:</h2>";
        echo "<table>";
        echo "<tr>
        <th>Matrik Number</th>
        <th>Book Title</th>
        <th>Borrow Date</th>
        <th>Due Date</th>
        <th>Book Status</th>
        </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["StudentID"] . "</td>";
            echo "<td>" . $row["BookName"] . "</td>";
            echo "<td>" . $row["BorrowDateTime"] . "</td>";
            echo "<td>" . $row["DueTime"] . "</td>";
            echo "<td>" . $row["Status"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No results found.";
    }
    
    // Close the prepared statement
    $stmt->close();
}
?>
