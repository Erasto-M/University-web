<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'config.php';
session_start();

$requestPath = $_SERVER['SCRIPT_NAME'];

// Allow public access to `course_catalog.php`
if ($requestPath === '/admin/course_catalog.php' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    fetchCourseCatalog();
} else {
    // Ensure user is logged in for other operations
    if (!isset($_SESSION['userId'])) {
        sendResponse(401, ['message' => 'Unauthorized access. Please log in.']);
    }

    // Handle operations based on the request method
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'update':
                updateCourse();
                break;
            case 'updateSection':
                updateCourseSection();
                break;
            case 'createSection':
                createCourseSection();
                break;
            case 'deleteSection':
                deleteCourseSection();
                break;
            default:
                sendResponse(400, ['message' => 'Invalid action specified.']);
        }
    }
}

// Function to fetch course catalog
function fetchCourseCatalog() {
    global $conn;

    try {
        $filters = [
            'semesterID' => $_GET['semesterID'] ?? '',
            'courseID' => $_GET['courseID'] ?? '',
            'crnNo' => $_GET['crnNo'] ?? '',
            'department' => $_GET['department'] ?? '',
            'professor' => $_GET['professor'] ?? '',
            'days' => $_GET['days'] ?? '',
        ];

        $sql = "SELECT 
                    cs.crnNo AS CRN,
                    c.courseName AS CourseName,
                    c.courseID AS CourseID,
                    cs.sectionNo AS SectionNumber,
                    d.deptName AS Department,
                    CONCAT(a.firstName, ' ', a.lastName) AS Professor,
                    GROUP_CONCAT(DISTINCT dy.weekDay ORDER BY dy.dayID) AS Days,
                    CONCAT(p.startTime, ' - ', p.endTime) AS TimeSlot,
                    r.roomNo AS RoomNumber,
                    b.buildingName AS BuildingName,
                    cs.availableSeats AS AvailableSeats,
                    sem.semesterName AS SemesterName
                FROM 
                    CourseSection cs
                JOIN Course c ON cs.courseID = c.courseID
                JOIN Department d ON c.deptID = d.deptID
                LEFT JOIN AppUser a ON cs.facultyID = a.userID
                LEFT JOIN TimeSlot ts ON cs.timeSlot = ts.timeSlotID
                LEFT JOIN TimeSlotDay tsd ON ts.timeSlotID = tsd.timeSlotID
                LEFT JOIN Day dy ON tsd.dayID = dy.dayID
                LEFT JOIN Period p ON ts.periods = p.periodID
                LEFT JOIN Room r ON cs.roomID = r.roomID
                LEFT JOIN Building b ON r.buildingID = b.buildingID
                LEFT JOIN Semester sem ON cs.semesterID = sem.semesterID
                WHERE 1=1";

        $params = [];
        $types = '';

        if ($filters['semesterID']) {
            $sql .= " AND cs.semesterID = ?";
            $params[] = $filters['semesterID'];
            $types .= 'i';
        }

        if ($filters['courseID']) {
            $sql .= " AND c.courseID = ?";
            $params[] = $filters['courseID'];
            $types .= 'i';
        }


        if ($filters['department']) {
            $sql .= " AND d.deptName LIKE ?";
            $params[] = "%" . $filters['department'] . "%";
            $types .= 's';
        }

        if ($filters['professor']) {
            $sql .= " AND CONCAT(a.firstName, ' ', a.lastName) LIKE ?";
            $params[] = "%" . $filters['professor'] . "%";
            $types .= 's';
        }

        if ($filters['days']) {
            $sql .= " AND dy.weekDay LIKE ?";
            $params[] = "%" . $filters['days'] . "%";
            $types .= 's';
        }
        if ($filters['crnNo']) {
            $sql .= " AND cs.crnNo = ?";
            $params[] = $filters['crnNo'];
            $types .= 'i';
        }
        
        $sql .= " GROUP BY cs.crnNo";

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $courses = [];
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }

        sendResponse(200, $courses);
    } catch (Exception $e) {
        error_log("Error fetching course catalog: " . $e->getMessage());
        sendResponse(500, ['message' => 'An error occurred while fetching the course catalog.']);
    }
}




// Function to send response
function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

// Update Course Information
function updateCourse() {
    global $conn;

    $inputData = json_decode(file_get_contents('php://input'), true);
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

    $sql_check = "SELECT * FROM Course WHERE courseID = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('i', $courseID);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        sendResponse(404, ['status' => 'error', 'message' => 'Course not found']);
    }

    $sql = "UPDATE Course 
            SET courseName = ?, numOfCredits = ?, courseLevel = ?, description = ? 
            WHERE courseID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $courseName, $numOfCredits, $courseLevel, $description, $courseID);

    if ($stmt->execute()) {
        sendResponse(200, ['status' => 'success', 'message' => 'Course updated successfully']);
    } else {
        error_log("Failed to update course with ID $courseID: " . $stmt->error);
        sendResponse(500, ['status' => 'error', 'message' => 'Failed to update course']);
    }

    $stmt->close();
}

// Update Course Section
function updateCourseSection() {
    global $conn;

    $crnNo = $_POST['crnNo'];
    $courseID = $_POST['courseID'];
    $sectionNo = $_POST['sectionNo'];
    $facultyID = $_POST['facultyID'];
    $timeSlot = $_POST['timeSlot'];
    $roomID = $_POST['roomID'];
    $availableSeats = $_POST['availableSeats'];
    $semesterID = $_POST['semesterID'];

    $sql_check = "SELECT * FROM CourseSection WHERE crnNo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('i', $crnNo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        sendResponse(404, ['status' => 'error', 'message' => 'Course section not found']);
    }

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

// Create Course Section
function createCourseSection() {
    global $conn;

    $courseID = $_POST['courseID'];
    $sectionNo = $_POST['sectionNo'];
    $facultyID = $_POST['facultyID'];
    $timeSlot = $_POST['timeSlot'];
    $roomID = $_POST['roomID'];
    $availableSeats = $_POST['availableSeats'];
    $semesterID = $_POST['semesterID'];

    $sql_check = "SELECT * FROM CourseSection WHERE courseID = ? AND sectionNo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('ii', $courseID, $sectionNo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        sendResponse(400, ['status' => 'error', 'message' => 'Course section already exists']);
    }

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

// Delete Course Section
function deleteCourseSection() {
    global $conn;

    $crnNo = $_POST['crnNo'];

    $sql_check = "SELECT * FROM CourseSection WHERE crnNo = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('i', $crnNo);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        sendResponse(404, ['status' => 'error', 'message' => 'Course section not found']);
    }

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
