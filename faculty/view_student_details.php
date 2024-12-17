<?php
// Database connection
$servername = "84.247.174.84";
$username = "ecoville"; // Your database username
$password = "ecoville"; // Your database password// Your database password
$dbname = "universitydb"; //

// Connect to the database
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Get the student ID and action from the query parameters
$studentId = isset($_GET['studentId']) ? intval($_GET['studentId']) : 0;
$action = isset($_GET['action']) ? $_GET['action'] : null;

// Ensure a valid student ID is provided
if ($studentId <= 0) {
    die("Invalid or missing Student ID.");
}

// Fetch the basic student details (always shown at the top of the page)
$studentQuery = "
    SELECT au.firstName, au.lastName, au.email
    FROM AppUser au
    INNER JOIN Student s ON au.userID = s.studentID
    WHERE s.studentID = ?
";
$stmt = $conn->prepare($studentQuery);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$studentResult = $stmt->get_result();
if ($studentResult && $row = $studentResult->fetch_assoc()) {
    $studentData = $row;
} else {
    die("Error: Student details not found.");
}

// Fetch personal information
if ($action === 'personal_info') {
    $personalInfoQuery = "
        SELECT au.firstName, au.lastName, au.email, au.gender, au.streetName,
               au.city, au.zipcode, au.DOB AS dateOfBirth, au.houseNo AS address
        FROM AppUser au
        INNER JOIN Student s ON au.userID = s.studentID
        WHERE s.studentID = ?
    ";
    $stmt = $conn->prepare($personalInfoQuery);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $personalInfo = $stmt->get_result()->fetch_assoc();
}

// Fetch degree audit
if ($action === 'degree_audit') {
    $degreeAuditQuery = "
        SELECT 
            c.courseName,
            IFNULL(sem.semesterName, 'N/A') AS semesterName,
            IFNULL(sem.semesterYear, 'N/A') AS semesterYear,
            CONCAT(f.firstName, ' ', f.lastName) AS professorName,
            cs.crnNo,
            CASE
                WHEN sh.grade IS NULL THEN 'In Progress'
                ELSE 'Completed'
            END AS courseStatus,
            IFNULL(sh.grade, 'Not Taken') AS earnedGrade
        FROM Course c
        LEFT JOIN StudentHistory sh ON c.courseID = sh.courseID AND sh.studentID = ?
        LEFT JOIN Semester sem ON sh.semesterID = sem.semesterID
        LEFT JOIN CourseSection cs ON c.courseID = cs.courseID
        LEFT JOIN AppUser f ON cs.facultyID = f.userID
    ";
    $stmt = $conn->prepare($degreeAuditQuery);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $degreeAudit = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fetch holds
if ($action === 'holds') {
    $holdsQuery = "
        SELECT h.holdType, h.description
        FROM StudentHold sh
        INNER JOIN Hold h ON sh.holdID = h.holdID
        WHERE sh.studentID = ?
    ";
    $stmt = $conn->prepare($holdsQuery);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $holds = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fetch semester schedule
if ($action === 'schedule') {
    $scheduleQuery = "
        SELECT c.courseName, e.crnNo, s.semesterName,
               CONCAT(au.firstName, ' ', au.lastName) AS facultyName
        FROM Enrollment e
        INNER JOIN CourseSection cs ON e.crnNo = cs.crnNo
        INNER JOIN Course c ON cs.courseID = c.courseID
        INNER JOIN Semester s ON cs.semesterID = s.semesterID
        INNER JOIN AppUser au ON cs.facultyID = au.userID
        WHERE e.studentID = ?
    ";
    $stmt = $conn->prepare($scheduleQuery);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $schedule = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Fetch transcript
if ($action === 'transcript') {
    $transcriptQuery = "
        SELECT c.courseName, sh.grade, sem.semesterName
        FROM StudentHistory sh
        INNER JOIN Course c ON sh.courseID = c.courseID
        INNER JOIN Semester sem ON sh.semesterID = sem.semesterID
        WHERE sh.studentID = ?
    ";
    $stmt = $conn->prepare($transcriptQuery);
    $stmt->bind_param("i", $studentId);
    $stmt->execute();
    $transcript = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1, h2 { color: #004d00; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .btn { background-color: #4CAF50; color: white; padding: 10px 15px; margin: 10px; border: none; text-decoration: none; }
        .btn:hover { background-color: #45a049; }
    </style>
</head>
<body>
    <h1>Student Details</h1>
    <p><strong>Full Name:</strong> <?= htmlspecialchars($studentData['firstName'] . ' ' . $studentData['lastName']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($studentData['email']) ?></p>

    <h2>Actions</h2>
    <a class="btn" href="?action=personal_info&studentId=<?= $studentId ?>">View Personal Info</a>
    <a class="btn" href="?action=degree_audit&studentId=<?= $studentId ?>">View Degree Audit</a>
    <a class="btn" href="?action=holds&studentId=<?= $studentId ?>">View Holds</a>
    <a class="btn" href="?action=schedule&studentId=<?= $studentId ?>">View Semester Schedule</a>
    <a class="btn" href="?action=transcript&studentId=<?= $studentId ?>">View Transcript</a>
    <a class="btn" href="http://localhost/faculty/Frontend/faculty_dashboard.html#advisees">Back</a>


    <?php if ($action === 'personal_info' && isset($personalInfo)): ?>
        <h2>Personal Information</h2>
        <p><strong>Gender:</strong> <?= htmlspecialchars($personalInfo['gender']); ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($personalInfo['dateOfBirth']); ?></p>
        <p><strong>Address:</strong> 
            <?= htmlspecialchars($personalInfo['address'] . ', ' . $personalInfo['streetName'] . ', ' . $personalInfo['city'] . ', ' . $personalInfo['zipcode']); ?>
        </p>
    <?php elseif ($action === 'degree_audit'): ?>
        <h2>Degree Audit</h2>
        <table>
            <tr><th>Course Name</th><th>Semester</th><th>Year</th><th>Professor</th><th>CRN</th><th>Status</th><th>Grade</th></tr>
            <?php foreach ($degreeAudit as $course): ?>
                <tr>
                    <td><?= htmlspecialchars($course['courseName']) ?></td>
                    <td><?= htmlspecialchars($course['semesterName']) ?></td>
                    <td><?= htmlspecialchars($course['semesterYear']) ?></td>
                    <td><?= htmlspecialchars($course['professorName']) ?></td>
                    <td><?= htmlspecialchars($course['crnNo']) ?></td>
                    <td><?= htmlspecialchars($course['courseStatus']) ?></td>
                    <td><?= htmlspecialchars($course['earnedGrade']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($action === 'holds'): ?>
        <h2>Holds</h2>
        <table>
            <tr><th>Hold Type</th><th>Description</th></tr>
            <?php foreach ($holds as $hold): ?>
                <tr><td><?= htmlspecialchars($hold['holdType']); ?></td><td><?= htmlspecialchars($hold['description']); ?></td></tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($action === 'schedule'): ?>
        <h2>Your Semester Schedule</h2>
        <table>
            <tr><th>Course Name</th><th>Section</th><th>Semester</th><th>Faculty Name</th></tr>
            <?php foreach ($schedule as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['courseName']); ?></td>
                    <td><?= htmlspecialchars($row['crnNo']); ?></td>
                    <td><?= htmlspecialchars($row['semesterName']); ?></td>
                    <td><?= htmlspecialchars($row['facultyName']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($action === 'transcript'): ?>
        <h2>Unofficial Transcript</h2>
        <table>
            <tr><th>Course Name</th><th>Grade</th><th>Semester</th></tr>
            <?php foreach ($transcript as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['courseName']); ?></td>
                    <td><?= htmlspecialchars($row['grade']); ?></td>
                    <td><?= htmlspecialchars($row['semesterName']); ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
