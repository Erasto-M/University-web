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

// Include courseID in the SELECT statement
$query = "SELECT c.courseID, c.courseName, cs.sectionNo, cs.availableSeats, s.semesterName
          FROM Enrollment e
          INNER JOIN CourseSection cs ON e.crnNo = cs.crnNo
          INNER JOIN Course c ON cs.courseID = c.courseID
          INNER JOIN Semester s ON cs.semesterID = s.semesterID
          WHERE e.studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row; // Fetch all data, including courseID
}

echo json_encode($courses);
?>
