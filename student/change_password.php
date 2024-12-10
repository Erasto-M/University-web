<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include('db_connection.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['userId'];
$newPassword = $_POST['newPassword'] ?? null;

if (!$newPassword) {
    echo json_encode(['error' => 'New password is required']);
    exit();
}

$query = "UPDATE AppUser SET password = ? WHERE userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $newPassword, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Password updated successfully']);
} else {
    echo json_encode(['error' => 'Failed to update password']);
}
?>
