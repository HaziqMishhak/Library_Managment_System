<?php
include('dbconnect.php');

$user_id = $_SESSION["user_id"];

$query = "SELECT name, email FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $user_name, $user_email);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
?>
