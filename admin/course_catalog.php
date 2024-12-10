<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'config.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['userId'])) {
    sendResponse(401, ['message' => 'Unauthorized access. Please log in.']);
}

$sql = "SELECT c.courseID, c.courseName, c.numOfCredits, c.courseLevel, c.description, d.deptName 
        FROM Course c
        JOIN Department d ON c.deptID = d.deptID";

$result = $conn->query($sql);

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

sendResponse(200, $courses);

function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

// Update Course Information
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update') {
    updateCourse();
}
function updateCourse() {
    global $conn;

    // Sanitize and collect POST data
    $inputData = json_decode(file_get_contents('php://input'), true); // Get the raw POST data
    if (isset($inputData['courseID'], $inputData['courseName'], $inputData['numOfCredits'], $inputData['courseLevel'], $inputData['description'])) {
        $courseID = $inputData['courseID'];
        $courseName = $inputData['courseName'];
        $numOfCredits = $inputData['numOfCredits'];
        $courseLevel = $inputData['courseLevel'];
        $description = $inputData['description'];
    } else {
        sendResponse(400, ['status' => 'error', 'message' => 'Missing required fields.']);
        return;
    }

    // Check if the course exists before updating
    $sql_check = "SELECT * FROM Course WHERE courseID = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('i', $courseID);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        sendResponse(404, ['status' => 'error', 'message' => 'Course not found']);
    }

    // Update the course details
    $sql = "UPDATE Course 
            SET courseName = ?, numOfCredits = ?, courseLevel = ?, description = ? 
            WHERE courseID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $courseName, $numOfCredits, $courseLevel, $description, $courseID);

    if ($stmt->execute()) {
        sendResponse(200, ['status' => 'success', 'message' => 'Course updated successfully']);
    } else {
        // Log the error if update fails
        error_log("Failed to update course with ID $courseID: " . $stmt->error);
        sendResponse(500, ['status' => 'error', 'message' => 'Failed to update course']);
    }

    $stmt->close();
}
function updateCourseSection() {
    global $conn;

    $crnNo = $_POST['crnNo'];  // Course Section ID (CRN)
    $courseID = $_POST['courseID'];
    $sectionNo = $_POST['sectionNo'];
    $facultyID = $_POST['facultyID'];
    $timeSlot = $_POST['timeSlot'];
    $roomID = $_POST['roomID'];
    $availableSeats = $_POST['availableSeats'];
    $semesterID = $_POST['semesterID'];

    // Check if the course section exists
    $sql_check = "SELECT * FROM CourseSection WHERE crnNo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('i', $crnNo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        sendResponse(404, ['status' => 'error', 'message' => 'Course section not found']);
    }

    // Proceed with updating if the course section exists
    $sql = "UPDATE CourseSection 
            SET courseID = ?, sectionNo = ?, facultyID = ?, timeSlot = ?, roomID = ?, availableSeats = ?, semesterID = ? 
            WHERE crnNo = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiiiiii', $courseID, $sectionNo, $facultyID, $timeSlot, $roomID, $availableSeats, $semesterID, $crnNo);

    if ($stmt->execute()) {
        sendResponse(200, ['status' => 'success', 'message' => 'Course section updated successfully']);
    } else {
        sendResponse(500, ['status' => 'error', 'message' => 'Failed to update course section']);
    }

    $stmt->close();
}

function createCourseSection() {
    global $conn;

    $courseID = $_POST['courseID'];
    $sectionNo = $_POST['sectionNo'];
    $facultyID = $_POST['facultyID'];
    $timeSlot = $_POST['timeSlot'];
    $roomID = $_POST['roomID'];
    $availableSeats = $_POST['availableSeats'];
    $semesterID = $_POST['semesterID'];

    // Check if the course section already exists
    $sql_check = "SELECT * FROM CourseSection WHERE courseID = ? AND sectionNo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('ii', $courseID, $sectionNo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        sendResponse(400, ['status' => 'error', 'message' => 'Course section already exists']);
    }

    // Insert the new course section
    $sql = "INSERT INTO CourseSection (courseID, sectionNo, facultyID, timeSlot, roomID, availableSeats, semesterID) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiiiii', $courseID, $sectionNo, $facultyID, $timeSlot, $roomID, $availableSeats, $semesterID);

    if ($stmt->execute()) {
        sendResponse(201, ['status' => 'success', 'message' => 'Course section created successfully']);
    } else {
        sendResponse(500, ['status' => 'error', 'message' => 'Failed to create course section']);
    }

    $stmt->close();
}



function deleteCourseSection() {
    global $conn;

    $crnNo = $_POST['crnNo'];

    // Check if the course section exists
    $sql_check = "SELECT * FROM CourseSection WHERE crnNo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('i', $crnNo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        sendResponse(404, ['status' => 'error', 'message' => 'Course section not found']);
    }

    // Delete the course section
    $sql = "DELETE FROM CourseSection WHERE crnNo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $crnNo);

    if ($stmt->execute()) {
        sendResponse(200, ['status' => 'success', 'message' => 'Course section deleted successfully']);
    } else {
        sendResponse(500, ['status' => 'error', 'message' => 'Failed to delete course section']);
    }

    $stmt->close();
}

?>