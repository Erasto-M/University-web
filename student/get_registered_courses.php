<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include('db_connection.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}


// Get the request method
$requestMethod = $_SERVER ['REQUEST_METHOD'];

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

    $sql = "
    SELECT 
        cs.crnNo, 
        cs.courseID, -- Added courseID here
        c.courseName, 
        cs.sectionNo, 
        r.roomNo, 
        b.buildingName, 
        u.firstName, 
        u.lastName, 
        cs.availableSeats, 
        s.semesterName,
        COALESCE(GROUP_CONCAT(d.weekDay ORDER BY d.dayID), 'N/A') AS days,
        COALESCE(p.startTime, 'N/A') AS startTime,
        COALESCE(p.endTime, 'N/A') AS endTime
    FROM CourseSection cs
    JOIN Course c ON cs.courseID = c.courseID
    LEFT JOIN Room r ON cs.roomID = r.roomID
    LEFT JOIN Building b ON r.buildingID = b.buildingID
    LEFT JOIN AppUser u ON cs.facultyID = u.userID
    LEFT JOIN Semester s ON cs.semesterID = s.semesterID
    LEFT JOIN TimeSlot ts ON cs.timeSlot = ts.timeSlotID
    LEFT JOIN TimeSlotDay tsd ON ts.timeSlotID = tsd.timeSlotID
    LEFT JOIN Day d ON tsd.dayID = d.dayID
    LEFT JOIN Period p ON ts.periods = p.periodID
    GROUP BY cs.crnNo;

    ";
    
    $result = $conn->query($sql);
    
    $courseSections = [];
    while ($row = $result->fetch_assoc()) {
        $courseSections[] = [
            'crnNo' => $row['crnNo'],
            'courseID' => $row['courseID'], // Ensure courseID is added to the response
            'courseName' => $row['courseName'],
            'sectionNo' => $row['sectionNo'],
            'roomNo' => $row['roomNo'],
            'buildingName' => $row['buildingName'],
            'facultyName' => $row['firstName'] . ' ' . $row['lastName'],
            'availableSeats' => $row['availableSeats'],
            'semesterName' => $row['semesterName'],
            'days' => $row['days'],
            'startTime' => $row['startTime'],
            'endTime' => $row['endTime'],
        ];
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

