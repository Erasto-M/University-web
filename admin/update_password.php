<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database connection
include 'config.php';

// Ensure user is logged in and the request method is POST
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON data from the request body
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['currentPassword']) && isset($data['newPassword'])) {
        $currentPassword = $data['currentPassword'];
        $newPassword = $data['newPassword'];
        $userID = $_SESSION['userID'];  // Assuming userID is stored in session after login

        // Check current password
        $sql = "SELECT password FROM AppUser WHERE userID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Check if the current password matches
            if (!password_verify($currentPassword, $row['password'])) {
                sendResponse(400, ['message' => 'Current password is incorrect']);
                exit();
            }
        } else {
            sendResponse(404, ['message' => 'User not found']);
            exit();
        }

        // Update the password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateSql = "UPDATE AppUser SET password = ? WHERE userID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param('si', $hashedPassword, $userID);

        if ($updateStmt->execute()) {
            sendResponse(200, ['status' => 'success', 'message' => 'Password updated successfully']);
        } else {
            sendResponse(500, ['status' => 'error', 'message' => 'Failed to update password']);
        }

        $stmt->close();
        $updateStmt->close();
    } else {
        sendResponse(400, ['message' => 'Required data missing']);
    }
} else {
    sendResponse(405, ['message' => 'Method Not Allowed']);
}

// Helper function to send JSON responses
function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
}
?>
