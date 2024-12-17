<?php
session_start();
header('Content-Type: application/json');

// Database Configuration
$servername = "84.247.174.84";
$username = "ecoville"; // Your database username
$password = "ecoville"; // Your database password// Your database password
$dbname = "universitydb";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Fetch student roster and course name for a specific CRN
        if (isset($_GET['crnNo'])) {
            $crnNo = $_GET['crnNo'];

            $sql = "
                SELECT e.studentID, CONCAT(a.firstName, ' ', a.lastName) AS studentName, 
                       e.grade, c.courseName
                FROM Enrollment e
                JOIN AppUser a ON e.studentID = a.userID
                JOIN CourseSection cs ON e.crnNo = cs.crnNo
                JOIN Course c ON cs.courseID = c.courseID
                WHERE e.crnNo = :crnNo
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->execute(['crnNo' => $crnNo]);

            $roster = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($roster) {
                echo json_encode(['status' => 'success', 'roster' => $roster, 'courseName' => $roster[0]['courseName']]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'No students found for this section']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Missing CRN parameter']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update student grades
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['crnNo'], $data['grades'])) {
            foreach ($data['grades'] as $studentID => $grade) {
                $updateSQL = "UPDATE Enrollment SET grade = :grade WHERE crnNo = :crnNo AND studentID = :studentID";
                $stmt = $pdo->prepare($updateSQL);
                $stmt->execute(['grade' => $grade, 'crnNo' => $data['crnNo'], 'studentID' => $studentID]);
            }

            echo json_encode(['status' => 'success', 'message' => 'Grades updated successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid data provided']);
        }
    }
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database Error: ' . $e->getMessage()]);
}
?>
