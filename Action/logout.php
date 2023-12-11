<?php
// Start session
session_start();

// Destroy session and redirect to login.html
session_destroy();
header("Location: ../Login/login.html");
?>