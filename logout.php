<?php
require 'db_connection.php'; // Ensure this path is correct
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();
}

// Redirect the user to the login page
header("Location: login.php"); // Ensure this path is correct
exit;
?>
