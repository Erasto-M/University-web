<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include('db_connection.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$searchCriteria = $_GET['criteria'] ?? null;
$searchValue = $_GET['value'] ?? null;

if (!$searchCriteria || !$searchValue) {
    echo json_encode(['error' => 'Search criteria and value are required']);
    exit();
}

$query = "SELECT cs.crnNo, c.courseName, ts.startTime, ts.endTime, d.weekDay, au.firstName AS professorFirstName, au.lastName AS professorLastName
          FROM CourseSection cs
          INNER JOIN Course c ON cs.courseID = c.courseID
          INNER JOIN TimeSlot ts ON cs.timeSlot = ts.timeSlotID
          INNER JOIN Day d ON ts.days = d.dayID
          INNER JOIN AppUser au ON cs.facultyID = au.userID
          WHERE $searchCriteria = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $searchValue);
$stmt->execute();
$result = $stmt->get_result();

$schedule = [];
while ($row = $result->fetch_assoc()) {
    $schedule[] = $row;
}

echo json_encode($schedule);
?>
