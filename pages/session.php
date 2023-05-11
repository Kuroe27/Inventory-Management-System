<?php
// Start the session
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['loggedin'])) {
    // Redirect the user to the login page
    header("Location: ../user/login.php");
    exit;
}
?>
