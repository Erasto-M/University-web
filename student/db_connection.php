<?php

header("Access-Control-Allow-Origin: *");  // Allow all origins, or specify your React app's URL
header("Content-Type: application/json");  // Ensure the response is in JSON format

$servername = "84.247.174.84";
$username = "ecoville"; // Your database username
$password = "ecoville"; // Your database password
$dbname = "universitydb"; 
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
