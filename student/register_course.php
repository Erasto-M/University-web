<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");

include('db_connection.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($_SESSION['userId'])) {
        echo json_encode(["success" => false, "errorCode" => "NOT_LOGGED_IN"]);
        exit();
    }

    if (!isset($input['crn'])) {
        echo json_encode(["success" => false, "errorCode" => "INVALID_INPUT"]);
        exit();
    }

    $userId = $_SESSION['userId'];
    $crn = intval($input['crn']);

    // Check if the course section exists
    $query = "SELECT * FROM CourseSection WHERE crnNo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $crn);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "errorCode" => "COURSE_NOT_FOUND"]);
        exit();
    }

    $course = $result->fetch_assoc();

    // Check if the student already has a hold
    $holdQuery = "SELECT * FROM StudentHold WHERE studentID = ?";
    $holdStmt = $conn->prepare($holdQuery);
    $holdStmt->bind_param("i", $userId);
    $holdStmt->execute();
    $holdResult = $holdStmt->get_result();

    if ($holdResult->num_rows > 0) {
        echo json_encode(["success" => false, "errorCode" => "STUDENT_HAS_HOLD"]);
        exit();
    }

    // Check for duplicate registration
    $duplicateQuery = "SELECT * FROM Enrollment WHERE studentID = ? AND crnNo = ?";
    $duplicateStmt = $conn->prepare($duplicateQuery);
    $duplicateStmt->bind_param("ii", $userId, $crn);
    $duplicateStmt->execute();
    $duplicateResult = $duplicateStmt->get_result();

    if ($duplicateResult->num_rows > 0) {
        echo json_encode(["success" => false, "errorCode" => "ALREADY_REGISTERED"]);
        exit();
    }

    // Check if the student meets prerequisites
    $prerequisiteQuery = "
        SELECT cp.prerequisiteCourseID
        FROM CoursePrerequisites cp
        LEFT JOIN StudentHistory sh 
        ON cp.prerequisiteCourseID = sh.courseID AND sh.studentID = ?
        WHERE cp.courseID = ? AND (sh.grade IS NULL OR sh.grade < cp.minimumGrade)
    ";
    $prerequisiteStmt = $conn->prepare($prerequisiteQuery);
    $prerequisiteStmt->bind_param("ii", $userId, $course['courseID']);
    $prerequisiteStmt->execute();
    $prerequisiteResult = $prerequisiteStmt->get_result();

    if ($prerequisiteResult->num_rows > 0) {
        echo json_encode(["success" => false, "errorCode" => "PREREQUISITES_NOT_MET"]);
        exit();
    }

    // Check if there are available seats
    if ($course['availableSeats'] <= 0) {
        echo json_encode(["success" => false, "errorCode" => "NO_SEATS_AVAILABLE"]);
        exit();
    }

    // Register the student for the course section
    $insertQuery = "INSERT INTO Enrollment (studentID, crnNo, dateOfEnrollment) VALUES (?, ?, NOW())";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("ii", $userId, $crn);

    if ($insertStmt->execute()) {
        // Update available seats
        $updateSeatsQuery = "UPDATE CourseSection SET availableSeats = availableSeats - 1 WHERE crnNo = ?";
        $updateSeatsStmt = $conn->prepare($updateSeatsQuery);
        $updateSeatsStmt->bind_param("i", $crn);
        $updateSeatsStmt->execute();

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "errorCode" => "DB_ERROR"]);
    }
} else {
    echo json_encode(["success" => false, "errorCode" => "INVALID_REQUEST_METHOD"]);
}
?>
