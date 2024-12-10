<?php
session_start();
// Check if the admin is logged in
if (!isset($_SESSION['userId']) || $_SESSION['userType'] !== 'Admin') {
    header('Location: login.php');  // Redirect to login if not logged in as admin
    exit();
}

?>
