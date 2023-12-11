<?php
include("dbconnect.php");
$response = [];

// Check if the request is a POST for either updating or deleting
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if it's an update action
    if (isset($_POST['borrowID']) && !isset($_POST['action'])) {
        // Get the POST data for update
        $borrowDateTime = $_POST['borrowDateTime'];
        $dueTime = $_POST['dueTime'];
        $borrowID = $_POST['borrowID'];

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE BorrowBook SET BorrowDateTime = ?, DueTime = ? WHERE BorrowID = ?");
        $stmt->bind_param("ssi", $borrowDateTime, $dueTime, $borrowID);

        // Execute the query and check if it's successful
        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Record updated successfully.'];
        } else {
            $response = ['success' => false, 'message' => 'Error updating record: ' . $conn->error];
        }
        $stmt->close();
    }
    // Check if it's a delete action
    elseif (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['BorrowID'])) {
        $borrowID = $_POST['BorrowID'];

        $stmt = $conn->prepare("DELETE FROM BorrowBook WHERE BorrowID = ?");
        $stmt->bind_param("i", $borrowID);

        // Execute the query and check if it's successful
        if ($stmt->execute()) {
            $response = ['success' => true, 'message' => 'Record deleted successfully.'];
        } else {
            if ($conn->errno == 1451) {
                $response = ['success' => false, 'message' => 'Error: This record cannot be deleted due to existing references in other tables.'];
            } else {
                $response = ['success' => false, 'message' => 'Error deleting record: ' . $conn->error];
            }
        }
        $stmt->close();
    }
    // Return the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
}

// Close the connection
$conn->close();
?>
