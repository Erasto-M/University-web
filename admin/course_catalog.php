<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'config.php';
// session_start();

// // Debug session to ensure user is logged in
// if (!isset($_SESSION['userId'])) {
//     error_log("Unauthorized access attempt");
//     sendResponse(401, ['message' => 'Unauthorized access. Please log in.']);
// }

// SQL query to fetch courses and their associated department names
$sql = "SELECT c.courseID, c.courseName, c.numOfCredits, c.courseLevel, c.description, d.deptName 
        FROM Course c
        JOIN Department d ON c.deptID = d.deptID";

// Execute the SQL query
$result = $conn->query($sql);

// Check if the query executed successfully
if (!$result) {
    error_log("SQL Error: " . $conn->error);
    sendResponse(500, ['error' => 'Query execution failed']);
}

// Fetch results and store them in the $courses array
$courses = [];
while ($row = $result->fetch_assoc()) {
    error_log("Fetched row: " . print_r($row, true)); // Debug: Log each row
    $courses[] = $row;
}

// If no courses are found, return a 404 response
if (empty($courses)) {
    sendResponse(404, ['message' => 'No courses found']);
}
echo json_encode($courses);
// Return the courses in the response
// sendResponse(200, $courses);

// // Function to send JSON responses
// function sendResponse($statusCode, $data) {
//     http_response_code($statusCode);
//     echo json_encode($data);
//     exit();
// }

// Update course details (only accessible via POST request with action 'update')
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update') {
    updateCourse();
}

// Update course details in the database
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

// Additional functions for managing course sections can go here...

?>