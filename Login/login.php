<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Including database connection
include('dbconnect.php');

// Check if the form has been submitted
if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Using prepared statements to fetch data to prevent SQL injection
    $query = "SELECT id, password FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    // Binding the parameter
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    // Bind the result to variables
    mysqli_stmt_bind_result($stmt, $userId, $hashedPassword);
    mysqli_stmt_fetch($stmt);

    // Verifying the password
    if(password_verify($password, $hashedPassword)) {
        // If password is correct, start a session and redirect to menu.html
        session_start();
        $_SESSION["user_id"] = $userId;
        
        // Set client-side session
        echo '<script>sessionStorage.setItem("isLoggedIn", "true"); window.location.href = " ../menu/Borrow.php ";</script>';
    } else {
        // Popup error message
        echo "<script>alert('Your email or password might be wrong.'); history.go(-1);</script>";
    }

    // Closing the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>
