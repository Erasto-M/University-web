<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
include 'config.php';

session_start(); // Ensure session is started

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $currentPassword = $data['currentPassword'];
    $newPassword = $data['newPassword'];
    $userID = $_SESSION['userID']; // Assume the user ID is stored in the session after login

    // Validate current password
    $sql = "SELECT password FROM AppUser WHERE userID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
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
        sendResponse(200, ['message' => 'Password updated successfully']);
    } else {
        sendResponse(500, ['message' => 'Failed to update password']);
    }

    $stmt->close();
    $updateStmt->close();
} else {
    sendResponse(405, ['message' => 'Method Not Allowed']);
}

// Helper function to send JSON responses
function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
}
?>
