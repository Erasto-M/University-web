<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'config.php';
session_start();

// Ensure user is logged in
if (!isset($_SESSION['userId'])) {
    sendResponse(401, ['message' => 'Unauthorized access. Please log in.']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete') {
    deleteCourseSection();
}

function deleteCourseSection() {
    global $conn;

    // Sanitize and collect POST data
    $inputData = json_decode(file_get_contents('php://input'), true);
    if (isset($inputData['crnNo'])) {
        $crnNo = $inputData['crnNo'];
    } else {
        sendResponse(400, ['status' => 'error', 'message' => 'Missing CRN number.']);
        return;
    }

    // Check if the course section exists before deleting
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

// Helper function to send response
function sendResponse($statusCode, $data) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}
?>
