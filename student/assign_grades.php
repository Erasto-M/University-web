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
$studentID = $_POST['studentID'] ?? null;
$grade = $_POST['grade'] ?? null;

if (!$crnNo || !$studentID || !$grade) {
    echo json_encode(['error' => 'CRN, student ID, and grade are required']);
    exit();
}

$query = "UPDATE Enrollment SET grade = ? WHERE crnNo = ? AND studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("sii", $grade, $crnNo, $studentID);

if ($stmt->execute()) {
    echo json_encode(['success' => 'Grade assigned successfully']);
} else {
    echo json_encode(['error' => 'Failed to assign grade']);
}
?>
