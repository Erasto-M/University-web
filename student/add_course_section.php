<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include('db_connection.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$crnNo = $_POST['crnNo'] ?? null;
$userId = $_SESSION['userId'];

if (!$crnNo) {
    echo json_encode(['error' => 'CRN number is required']);
    exit();
}

$query = "INSERT INTO Enrollment (studentID, crnNo, dateOfEnrollment) VALUES (?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userId, $crnNo);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Course added successfully']);
} else {
    echo json_encode(['error' => 'Failed to add course']);
}
?>
