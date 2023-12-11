<?php
// Include database connection
include 'dbconnect.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$response = ['success' => false, 'message' => 'An error occurred.'];

// Check if the form was submitted correctly with the expected POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['studentId'], $_FILES['receiptImage'])) {
    $studentId = $_POST['studentId'];
    $totalAmount = $_POST['totalAmount']; // Ensure this value is being passed from the form
    $numberOfBooks = $_POST['numberOfBooks'];
    $receiptImage = $_FILES['receiptImage'];

    // Validate studentId and totalAmount...
    if (empty($studentId)) {
        $response['message'] = 'Student ID is required.';
    } elseif (empty($totalAmount)) {
        $response['message'] = 'Total amount is required.';
    } else {
        // Proceed with file upload and database update...

        // Define upload directory and make sure it exists
        $uploadDir = "../upload/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true); // permissions need to be set appropriately
        }

        // Validate file upload
        if ($receiptImage['error'] !== UPLOAD_ERR_OK) {
            $response['message'] = 'An error occurred with the file upload.';
        } else {
            // Check file size
            if ($receiptImage['size'] > 5000000) { // 5MB limit
                $response['message'] = 'File is too large.';
            } else {
                // Determine file extension based on MIME type
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $fileContents = file_get_contents($receiptImage['tmp_name']);
                $mime = $finfo->buffer($fileContents);
                $validExtensions = [
                    'jpg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                ];
                $fileExt = array_search($mime, $validExtensions, true);

                // Validate file extension
                if (false === $fileExt) {
                    $response['message'] = 'Invalid file format.';
                } else {
                    // Construct a unique file name to prevent overwriting and move file
                    $newFilename = sha1_file($receiptImage['tmp_name']) . '.' . $fileExt;
                    $targetFilePath = $uploadDir . $newFilename;

                    if (move_uploaded_file($receiptImage['tmp_name'], $targetFilePath)) {
                        // File moved successfully, proceed with database update
                        $conn->begin_transaction();

                        // Prepare SQL to insert payment transaction record
                        $insertTransactionSql = "
                            INSERT INTO PaymentTransaction (StudentID, TotalAmount, NumberOfBooks, ReceiptImagePath, PaymentDate)
                            VALUES (?, ?, ?, ?, NOW());
                        ";
                        $stmt = $conn->prepare($insertTransactionSql);
                        $stmt->bind_param("sdis", $studentId, $totalAmount, $numberOfBooks, $targetFilePath);
                        $stmt->execute();

                        // Get the last inserted ID for the transaction
                        $transactionId = $conn->insert_id;

                        // Prepare SQL to update the fines
                        $updateFinesSql = "
                            UPDATE Fine
                            INNER JOIN ReturnBook ON Fine.ReturnID = ReturnBook.ReturnID
                            INNER JOIN BorrowBook ON ReturnBook.BorrowID = BorrowBook.BorrowID
                            INNER JOIN Students ON BorrowBook.StudentID = Students.StudentID
                            SET Fine.PaymentStatus = 'Paid', Fine.TransactionID = ?
                            WHERE Students.StudentID = ? AND Fine.PaymentStatus = 'Pending';
                        ";
                        $updateStmt = $conn->prepare($updateFinesSql);
                        $updateStmt->bind_param("is", $transactionId, $studentId);
                        $updateStmt->execute();

                        if ($stmt->affected_rows > 0 && $updateStmt->affected_rows > 0) {
                            // Both operations were successful
                            $conn->commit();
                            $response = ['success' => true, 'message' => 'Payment processed successfully.'];
                        } else {
                            // Something went wrong, roll back the transaction
                            $conn->rollback();
                            $response['message'] = 'Could not update fines.';
                        }

                        // Close the statement handles
                        $stmt->close();
                        $updateStmt->close();
                    } else {
                        $response['message'] = 'Failed to move uploaded file.';
                    }
                }
            }
        }
    }

    // Close the database connection
    $conn->close();
} else {
    $response['message'] = 'Invalid request method or missing fields.';
}

// Return the response in JSON format
echo json_encode($response);
?>
