<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Include database connection
include 'config.php';

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        createCourseSection();
        break;
    case 'GET':
        readCourseSections();
        break;
    case 'PUT':
        updateCourseSection();
        break;
    case 'DELETE':
        deleteCourseSection();
        break;
    default:
        sendResponse(405, ['message' => 'Method Not Allowed']);
        break;
}

// **CREATE**: Add a new course section to the master schedule
function createCourseSection() {
    global $conn;

    // Decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        sendResponse(400, ['message' => 'Invalid JSON input']);
    }

    $courseID = $input['courseID'];
    $sectionNo = $input['sectionNo'];
    $facultyID = $input['facultyID'];
    $timeSlot = $input['timeSlot'];
    $roomID = $input['roomID'];
    $availableSeats = $input['availableSeats'];
    $semesterID = $input['semesterID'];

    $sql = "INSERT INTO CourseSection (courseID, sectionNo, facultyID, timeSlot, roomID, availableSeats, semesterID) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiiiii', $courseID, $sectionNo, $facultyID, $timeSlot, $roomID, $availableSeats, $semesterID);
    
    if ($stmt->execute()) {
        sendResponse(201, ['message' => 'Course Section created successfully']);
    } else {
        error_log("Database Error: " . $stmt->error); // Log the error
        sendResponse(500, ['message' => 'Failed to create course section']);
    }
    

    $stmt->close();
}

// **READ**: View all course sections from the master schedule
function readCourseSections() {
    global $conn;
    

    $sql = "SELECT cs.crnNo, c.courseName, cs.sectionNo, au.firstName, au.lastName, cs.timeSlot, r.roomNo, cs.availableSeats, s.semesterName 
            FROM CourseSection cs
            JOIN Course c ON cs.courseID = c.courseID
            JOIN AppUser au ON cs.facultyID = au.userID
            JOIN Room r ON cs.roomID = r.roomID
            JOIN Semester s ON cs.semesterID = s.semesterID";
    
    $result = $conn->query($sql);
    
    $courseSections = [];
    while ($row = $result->fetch_assoc()) {
        $courseSections[] = $row;
    }

    sendResponse(200, $courseSections);
}

// **UPDATE**: Update an existing course section in the master schedule
function updateCourseSection() {
    global $conn;

    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input) {
        sendResponse(400, ['message' => 'Invalid JSON input']);
    }

    $crnNo = $input['crnNo'];
    $courseID = $input['courseID'];
    $sectionNo = $input['sectionNo'];
    $facultyID = $input['facultyID'];
    $timeSlot = $input['timeSlot'];
    $roomID = $input['roomID'];
    $availableSeats = $input['availableSeats'];
    $semesterID = $input['semesterID'];

    $sql = "UPDATE CourseSection 
            SET courseID = ?, sectionNo = ?, facultyID = ?, timeSlot = ?, roomID = ?, availableSeats = ?, semesterID = ? 
            WHERE crnNo = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iiiiiiii', $courseID, $sectionNo, $facultyID, $timeSlot, $roomID, $availableSeats, $semesterID, $crnNo);
    
    if ($stmt->execute()) {
        sendResponse(201, ['message' => 'Course Section created successfully']);
    } else {
        error_log("Database Error: " . $stmt->error); // Log the error
        sendResponse(500, ['message' => 'Failed to create course section']);
    }
    

    $stmt->close();
}

// **DELETE**: Delete a course section from the master schedule
function deleteCourseSection() {
    global $conn;

    parse_str(file_get_contents('php://input'), $input);
    $crnNo = $input['crnNo'];

    $sql = "DELETE FROM CourseSection WHERE crnNo = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $crnNo);
    
    if ($stmt->execute()) {
        sendResponse(200, ['message' => 'Course Section deleted successfully']);
    } else {
        sendResponse(500, ['message' => 'Failed to delete course section']);
    }

    $stmt->close();
}

// **RESPONSE FUNCTION**: Helper function to send an HTTP response
function sendResponse($statusCode, $data) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}
?>
