<?php
header("Access-Control-Allow-Origin: *");  // Allow all origins, or specify your React app's URL
header("Content-Type: application/json");  // Ensure the response is in JSON format

session_start();  // Start the session to access session variables

include 'db_connection.php';  // Include your database connection file

// Check if user is logged in and is a faculty member
if (!isset($_SESSION['userId']) || !isset($_SESSION['facultyFirstName']) || !isset($_SESSION['facultyLastName'])) {
    // Redirect to login page if not logged in
    header('Location: ./login.html');
    exit();
}

// Get studentId from GET request
if (isset($_GET['studentId'])) {
    $studentId = $_GET['studentId'];

    // Prepare the SQL query to fetch the student's semester schedule
    $sql = "SELECT s.ScheduleId, s.CourseName, s.CourseCode, s.Day, s.Time, r.RoomNumber
            FROM SemesterSchedule s
            JOIN Room r ON s.RoomId = r.RoomId
            WHERE s.StudentId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the student has a schedule
    if ($result->num_rows > 0) {
        $schedule = [];
        while ($row = $result->fetch_assoc()) {
            $schedule[] = $row;
        }

        // Return the student's schedule in JSON format
        echo json_encode([
            'status' => 'success',
            'message' => 'Student semester schedule fetched successfully.',
            'data' => $schedule
        ]);
    } else {
        // No schedule found for the student
        echo json_encode([
            'status' => 'error',
            'message' => 'No semester schedule found for the provided student ID.'
        ]);
    }
} else {
    // Missing studentId in the GET request
    echo json_encode([
        'status' => 'error',
        'message' => 'Student ID is required.'
    ]);
}

exit();
?>
