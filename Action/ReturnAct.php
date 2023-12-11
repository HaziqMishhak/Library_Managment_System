<?php
include("dbconnect.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['returnID'])) {
    $returnID = $_POST['returnID'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Check for paid fines before deleting
        $fineCheckStmt = $conn->prepare("SELECT FineID FROM Fine WHERE ReturnID = ? AND PaymentStatus = 'Paid'");
        $fineCheckStmt->bind_param("i", $returnID);
        $fineCheckStmt->execute();
        $fineResult = $fineCheckStmt->get_result();
        if ($fineResult->num_rows > 0) {
            // If there are paid fines, we should not delete the return record
            throw new Exception('Cannot delete return record with paid fines.');
        }

        // Delete associated Fine records that are not paid
        $deleteFineStmt = $conn->prepare("DELETE FROM Fine WHERE ReturnID = ? AND PaymentStatus != 'Paid'");
        $deleteFineStmt->bind_param("i", $returnID);
        $deleteFineStmt->execute();

        // Get the BorrowID from the ReturnBook record linked to the ReturnID
        $fetchBorrowIDStmt = $conn->prepare("SELECT BorrowID FROM BorrowBook WHERE BorrowID = (SELECT BorrowID FROM ReturnBook WHERE ReturnID = ? LIMIT 1)");
        $fetchBorrowIDStmt->bind_param("i", $returnID);
        $fetchBorrowIDStmt->execute();
        $borrowIDResult = $fetchBorrowIDStmt->get_result();
        $borrowID = null;
        if ($borrowIDResult->num_rows > 0) {
            $borrowIDData = $borrowIDResult->fetch_assoc();
            $borrowID = $borrowIDData['BorrowID'];
        }

        // Assuming there are no pending fines, proceed with deletion
        // Delete ReturnBook record
        $deleteReturnStmt = $conn->prepare("DELETE FROM ReturnBook WHERE ReturnID = ?");
        $deleteReturnStmt->bind_param("i", $returnID);
        $deleteReturnStmt->execute();

        // Update the BorrowBook record to set its status back to "Not Due"
        if ($borrowID !== null) {
            $updateBorrowBookStmt = $conn->prepare("UPDATE BorrowBook SET Status = 'Not Due' WHERE BorrowID = ?");
            $updateBorrowBookStmt->bind_param("i", $borrowID);
            $updateBorrowBookStmt->execute();
        }

        // Commit the transaction
        $conn->commit();
        $response = ['success' => true, 'message' => 'Return record and any non-paid fines deleted successfully, and BorrowBook status updated.'];
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();
        $response = ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
    }

    $conn->close();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
