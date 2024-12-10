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
$query = "SELECT c.courseName, e.crnNo, s.semesterName, au.firstName AS facultyFirstName, au.lastName AS facultyLastName
          FROM Enrollment e
          INNER JOIN CourseSection cs ON e.crnNo = cs.crnNo
          INNER JOIN Course c ON cs.courseID = c.courseID
          INNER JOIN Semester s ON cs.semesterID = s.semesterID
          INNER JOIN AppUser au ON cs.facultyID = au.userID
          WHERE e.studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$schedule = [];
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row;
}
echo json_encode($schedule);
?>
