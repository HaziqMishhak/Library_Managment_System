<?php
include('dbconnect.php'); 

// Handling manual book entry form
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['BookID'])) {
    $bookID = $_POST['BookID'];
    $bookName = $_POST['bookName'];
    $authorName = $_POST['authorName'];
    $publishDate = $_POST['publishDate'];

    // Check if BookID is numeric
    if(!is_numeric($bookID)) {
        echo "<script>alert('Error: BookID must be a number!'); window.location.href='../menu/Add.php';</script>";
        exit();
    }

    // Check if BookID already exists
    $checkSQL = "SELECT * FROM Books WHERE BookID = ?";
    $stmt = $conn->prepare($checkSQL);
    $stmt->bind_param("s", $bookID);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        echo "<script>alert('BookID already exists!'); window.location.href='../menu/Add.php';</script>";
    } else {
        $sql = "INSERT INTO Books (BookID, BookName, AuthorName, PublishDate) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $bookID, $bookName, $authorName, $publishDate);
        if ($stmt->execute()) {
            echo "<script>alert('New book added successfully!'); window.location.href='../menu/Add.php';</script>";
        } else {
            echo "<script>alert('Error: " . $conn->error . "'); window.location.href='../menu/Add.php';</script>";
        }
    }
}

// Handling CSV file upload
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $filename = $_FILES['file']['tmp_name'];
    $fileType = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    // Check if uploaded file is a CSV
    if (strtolower($fileType) !== 'csv') {
        echo "<script>alert('Please upload a valid CSV file.'); window.location.href='../menu/Add.php';</script>";
        exit();
    }

    $handle = fopen($filename, 'r');
    fgetcsv($handle);  // Skip the header row

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $bookID = $data[0];
        $bookName = $data[1];
        $authorName = $data[2];
        $publishDate = $data[3];

        // Check date format for PublishDate
        if(!preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $publishDate)) {
            echo "<script>alert('Error in CSV: Date format for \"" . $bookName . "\" must be yyyy-mm-dd!'); window.location.href='../menu/Add.php';</script>";
            fclose($handle);
            exit();
        }
        // Validate if BookID in the CSV is numeric
        if(!is_numeric($bookID)) {
            echo "<script>alert('Error in CSV: BookID for \"" . $bookName . "\" must be a number!'); window.location.href='../menu/Add.php';</script>";
            fclose($handle);
            exit();
        }
        
        // Check if BookID from the CSV already exists in the database
        $checkSQL = "SELECT * FROM Books WHERE BookID = ?";
        $stmt = $conn->prepare($checkSQL);
        $stmt->bind_param("s", $bookID);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            echo "<script>alert('BookID " . $bookID . " already exists!'); window.location.href='../menu/Add.php';</script>";
            fclose($handle);
            exit();
        } else {
            // If BookID from the CSV is new, insert the book data into the database
            $sql = "INSERT INTO Books (BookID, BookName, AuthorName, PublishDate) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $bookID, $bookName, $authorName, $publishDate);

            // Handle any errors while inserting the data
            if (!$stmt->execute()) {
                echo "<script>alert('Error with BookID " . $bookID . ": " . $conn->error . "'); window.location.href='../menu/Add.php';</script>";
                fclose($handle);
                exit();
            }
        }
    }
    fclose($handle);
    echo "<script>alert('CSV file imported successfully!'); window.location.href='../menu/Add.php';</script>";
}
?>
