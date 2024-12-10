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
$query = "SELECT au.firstName, au.lastName, au.email
          FROM Advisor a
          INNER JOIN AppUser au ON a.facultyID = au.userID
          WHERE a.studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $advisor = $result->fetch_assoc();
    echo json_encode($advisor);
} else {
    echo json_encode(['error' => 'Advisor not found']);
}
?>
