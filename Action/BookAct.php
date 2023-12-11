<?php
include("dbconnect.php");
$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if it's an update action
    if (isset($_POST['bookID']) && !isset($_POST['action'])) {
        // Get the POST data for update
        $bookName = $_POST['bookName'];
        $authorName = $_POST['authorName'];
        $publishDate = $_POST['publishDate']; // Ensure this matches the format expected by your database
        $bookID = $_POST['bookID'];

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE Books SET BookName = ?, AuthorName = ?, PublishDate = ? WHERE BookID = ?");
        $stmt->bind_param("sssi", $bookName, $authorName, $publishDate, $bookID);

        // Execute the query and check if it's successful
        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Book updated successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Error updating book: ' . $conn->error];
        }
        $stmt->close();
    }
    // Check if it's a delete action
    elseif (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['bookID'])) {
        $bookID = $_POST['bookID'];

        // Soft delete the book record by marking it as inactive
        $stmt = $conn->prepare("UPDATE Books SET IsActive = FALSE WHERE BookID = ?");
        $stmt->bind_param("i", $bookID);

        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Book marked as inactive successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Error marking book as inactive: ' . $conn->error];
        }
        $stmt->close();
    }
} else {
    $response = ['success' => false, 'message' => 'Invalid request method.'];
}

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>
