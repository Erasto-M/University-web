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

// Query for Degree Audit including detailed information
$query = "
    SELECT 
        c.courseID,
        c.courseName,
        CASE
            WHEN mr.majorID IS NOT NULL THEN 'Major'
            WHEN mnr.minorID IS NOT NULL THEN 'Minor'
        END AS courseType,
        mr.minimumGradeRequired AS majorGradeRequired,
        mnr.minimumGradeRequired AS minorGradeRequired,
        sh.grade AS earnedGrade,
        sh.semesterID,
        sem.semesterName,
        sem.semesterYear,
        cs.crnNo,
        CONCAT(f.firstName, ' ', f.lastName) AS professorName,
        CASE
            WHEN sh.grade IS NULL THEN 'In Progress'
            WHEN sh.grade < mr.minimumGradeRequired THEN 'Retaken'
            ELSE 'Completed'
        END AS courseStatus
    FROM Course c
    LEFT JOIN MajorRequirements mr ON c.courseID = mr.courseID
    LEFT JOIN MinorRequirements mnr ON c.courseID = mnr.courseID
    LEFT JOIN StudentHistory sh ON c.courseID = sh.courseID AND sh.studentID = ?
    LEFT JOIN Semester sem ON sh.semesterID = sem.semesterID
    LEFT JOIN CourseSection cs ON c.courseID = cs.courseID
    LEFT JOIN AppUser f ON cs.facultyID = f.userID
    WHERE (mr.majorID IN (SELECT majorID FROM StudentMajor WHERE studentID = ?) OR
           mnr.minorID IN (SELECT minorID FROM StudentMinor WHERE studentID = ?))
";
$stmt = $conn->prepare($query);
$stmt->bind_param("iii", $userId, $userId, $userId);
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
