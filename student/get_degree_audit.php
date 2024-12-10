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

$query = "SELECT mr.courseID, c.courseName, mr.minimumGradeRequired, 
                 CASE 
                     WHEN sh.grade IS NOT NULL THEN sh.grade 
                     ELSE 'Not Completed' 
                 END AS status
          FROM MajorRequirements mr
          INNER JOIN Course c ON mr.courseID = c.courseID
          LEFT JOIN StudentHistory sh ON mr.courseID = sh.courseID AND sh.studentID = ?
          INNER JOIN StudentMajor sm ON mr.majorID = sm.majorID
          WHERE sm.studentID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();

$degreeAudit = [];
while ($row = $result->fetch_assoc()) {
    $degreeAudit[] = $row;
}

echo json_encode($degreeAudit);

$stmt->close();
$conn->close();
?>
