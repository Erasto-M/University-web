<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include('db_connection.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Retrieve courseID from the GET request
$courseID = $_GET['courseID'] ?? null;

if (!$courseID) {
    echo json_encode(['error' => 'Course ID is required']);
    exit();
}

// Query to fetch prerequisites for the given courseID
$query = "
    SELECT p.prerequisiteCourseID, p.minimumGrade, c.courseName AS prerequisiteDescription
    FROM CoursePrerequisites p
    JOIN Course c ON p.prerequisiteCourseID = c.courseID
    WHERE p.courseID = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo json_encode(['error' => 'Failed to prepare the statement']);
    exit();
}

$stmt->bind_param("i", $courseID);
$stmt->execute();
$result = $stmt->get_result();

$prerequisites = [];
while ($row = $result->fetch_assoc()) {
    $prerequisites[] = [
        'prerequisiteDescription' => $row['prerequisiteDescription'],
        'minimumGrade' => $row['minimumGrade']
    ];
}

// Return prerequisites as JSON
echo json_encode($prerequisites);

$stmt->close();
$conn->close();
?>
