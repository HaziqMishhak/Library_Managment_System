<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('dbconnect.php'); // Include your database connection file

if(isset($_POST['signup'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Regular expression for validating email format
    $emailRegex = '/^[a-zA-Z0-9._-]+@kuis\.edu\.my$/';

    if (preg_match($emailRegex, $email)) {
        // Valid email format, proceed with registration and database insertion

        // Hash the password before storing it in the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare an SQL statement using prepared statements to prevent SQL injection
        $query = "INSERT INTO users (name, phone, email, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);

        // Bind parameters and execute the statement
        mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $email, $hashedPassword);
        
        if(mysqli_stmt_execute($stmt)) {
            echo "<script type='text/javascript'> alert('Registration successful!');
            window.location.href = 'login.html';
          </script>";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Invalid email format, show error message
        echo "<script type='text/javascript'> alert('Invalid email format. Please use a valid email address.');
        window.location.href = 'register.html';
          </script>";
    }
}

mysqli_close($conn); // Close the database connection
?>

