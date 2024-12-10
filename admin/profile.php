<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database connection
include 'config.php';

// Start session to access the logged-in user's session variables
session_start();

// Get the request method and action
$requestMethod = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Ensure the user is logged in
if (!isset($_SESSION['userId'])) {
    sendResponse(401, ['message' => 'Unauthorized access. Please log in.']);
}

// Retrieve the admin's user ID from the session
$adminID = $_SESSION['userId'];

if ($requestMethod === 'GET') {
    fetchProfile($adminID); // Pass the adminID to the function
} elseif ($requestMethod === 'POST' && $action === 'update') {
    updateProfile($adminID); // Pass the adminID to the function
} else {
    sendResponse(405, ['message' => 'Method Not Allowed']);
}

// Fetch profile details for the logged-in admin
function fetchProfile($adminID) {
    global $conn;

    $sql = "SELECT firstName, lastName, gender, DOB as dob, zipcode, phoneNo, email 
            FROM AppUser 
            WHERE userID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $adminID); // Bind adminID to fetch the correct profile
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        sendResponse(200, $row); // Send the profile details as JSON response
    } else {
        sendResponse(404, ['message' => 'Profile not found']);
    }

    $stmt->close();
}

// Update profile details for the logged-in admin
function updateProfile($adminID) {
    global $conn;

    // Ensure all fields are received in the POST data
    $firstName = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $zipcode = $_POST['zipcode'];
    $phoneNo = $_POST['phoneNo'];
    $email = $_POST['email'];

    $sql = "UPDATE AppUser 
            SET firstName = ?, gender = ?, DOB = ?, zipcode = ?, phoneNo = ?, email = ? 
            WHERE userID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssi', $firstName, $gender, $dob, $zipcode, $phoneNo, $email, $adminID);

    if ($stmt->execute()) {
        sendResponse(200, ['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        sendResponse(500, ['status' => 'error', 'message' => 'Failed to update profile']);
    }

    $stmt->close();
}

// Helper function to send response
function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}
?>
