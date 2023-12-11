<?php

//server detail
$servername = "localhost";
$username = "libraryu_admin";
$password = "";
$dbname = "libraryu_final";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
