<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include('db_connection.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Fetch majors
$queryMajors = "SELECT majorID, majorName, numOfCreditsRequired FROM Major";
$stmt = $conn->prepare($queryMajors);
$stmt->execute();
$result = $stmt->get_result();

$majors = [];
while ($row = $result->fetch_assoc()) {
    $majors[] = $row;
}

// Fetch minors
$queryMinors = "SELECT minorID, minorName, numOfCreditsRequired FROM Minor";
$stmt = $conn->prepare($queryMinors);
$stmt->execute();
$result = $stmt->get_result();

$minors = [];
while ($row = $result->fetch_assoc()) {
    $minors[] = $row;
}

echo json_encode(['majors' => $majors, 'minors' => $minors]);
?>
