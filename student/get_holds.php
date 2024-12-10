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

$query = "SELECT h.holdType, h.description, sh.dateOfHold
          FROM StudentHold sh
          INNER JOIN Hold h ON sh.holdID = h.holdID
          WHERE sh.studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$holds = [];
while ($row = $result->fetch_assoc()) {
    $holds[] = $row;
}

echo json_encode($holds);

$stmt->close();
$conn->close();
?>
