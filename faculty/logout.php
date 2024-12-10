<?php

header("Access-Control-Allow-Origin: *");  // Allow all origins, or specify your React app's URL
header("Content-Type: application/json");  // Ensure the response is in JSON format

session_start();  // Start the session

// Destroy the session to log out the faculty member
session_unset();  // Remove all session variables
session_destroy();  // Destroy the session

// Redirect to the login page
header('Location: faculty_login.html');  // Replace with your login page URL if necessary
exit();
?>
