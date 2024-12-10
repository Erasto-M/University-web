<?php
header("Access-Control-Allow-Origin: *");  // Allow all origins, or specify your React app's URL
header("Content-Type: application/json");  // Ensure the response is in JSON format

session_start();  // Start the session to access session variables

include 'db_connection.php';  // Include your database connection file

// Check if user is logged in and is a faculty member
if (!isset($_SESSION['userId']) || !isset($_SESSION['facultyId'])) {
    // Redirect to login page if not logged in
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in. Please log in first.'
    ]);
    exit();
}

// Get the faculty ID from session
$facultyId = $_SESSION['facultyId'];

// Check if the necessary POST data is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['courseSectionId']) && isset($_POST['studentId']) && isset($_POST['grade'])) {
    $courseSectionId = $_POST['courseSectionId'];
    $studentId = $_POST['studentId'];
    $grade = $_POST['grade'];  // Assuming grade is a string (e.g., 'A', 'B+', etc.)

    // Validate grade input
    $validGrades = ['A', 'B+', 'B', 'C+', 'C', 'D+', 'D', 'F'];
    if (!in_array($grade, $validGrades)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid grade. Please enter a valid grade (A, B+, B, C+, C, D+, D, F).'
        ]);
        exit();
    }

    // Check if the faculty is assigned to the course section
    $sql = "SELECT * FROM CourseSection WHERE CourseSectionId = ? AND FacultyId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $courseSectionId, $facultyId);
    $stmt->execute();
    $result = $stmt->get_result();

    // If the faculty is not assigned to this course section, return an error
    if ($result->num_rows === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'You are not authorized to assign grades for this course section.'
        ]);
        exit();
    }

    // Prepare the SQL query to insert or update grades for the student in the course section
    $sql = "INSERT INTO Grades (CourseSectionId, StudentId, Grade)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE Grade = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $courseSectionId, $studentId, $grade, $grade);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Grade assigned successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to assign grade. Please try again later.'
        ]);
    }
    exit();
} else {
    // If POST data is missing or invalid
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request. Please provide course section ID, student ID, and grade.'
    ]);
    exit();
}
?>
