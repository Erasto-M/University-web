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
$query = "SELECT c.courseName, sh.grade, s.semesterName
          FROM StudentHistory sh
          INNER JOIN Course c ON sh.courseID = c.courseID
          INNER JOIN Semester s ON sh.semesterID = s.semesterID
          WHERE sh.studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$transcript = [];
while ($row = $result->fetch_assoc()) {
    $transcript[] = $row;
}
echo json_encode($transcript);
?>
