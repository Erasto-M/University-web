<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'config.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['userId'])) {
    sendResponse(401, ['message' => 'Unauthorized access. Please log in.']);
}

$sql = "SELECT m.majorName, d.deptName, m.numOfCreditsRequired 
        FROM Major m
        JOIN Department d ON m.deptID = d.deptID";

$result = $conn->query($sql);

$majors = [];
while ($row = $result->fetch_assoc()) {
    $majors[] = $row;
}

sendResponse(200, $majors);

function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}
?>
