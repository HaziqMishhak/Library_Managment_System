<?php
function updateBorrowStatus($conn) {
    $currentDateTime = new DateTime();

    // Fetch all records from BorrowBook that are not 'Returned'
    $sqlToCheckStatus = "SELECT * FROM BorrowBook WHERE Status != 'Returned'";
    $resultToCheck = $conn->query($sqlToCheckStatus);

    if ($resultToCheck->num_rows > 0) {
        while($rowToCheck = $resultToCheck->fetch_assoc()) {
            $dueDateTime = new DateTime($rowToCheck['DueTime']);
            $borrowID = $rowToCheck['BorrowID'];

            // Check if the book has an entry in the ReturnBook table
            $checkReturn = "SELECT * FROM ReturnBook WHERE BorrowID = '$borrowID'";
            $returnResult = $conn->query($checkReturn);

            // If there's no entry in ReturnBook for the BorrowID
            if ($returnResult->num_rows == 0) {
                // If current date/time is past DueTime, set status to "Late"
                if ($currentDateTime > $dueDateTime) {
                    $updateStatus = "UPDATE BorrowBook SET Status = 'Late' WHERE BorrowID = '$borrowID'";
                    $conn->query($updateStatus);
                } else {
                    // Else, set status to "Not Due"
                    $updateStatus = "UPDATE BorrowBook SET Status = 'Not Due' WHERE BorrowID = '$borrowID'";
                    $conn->query($updateStatus);
                }
            }
        }
    }
}

?>
