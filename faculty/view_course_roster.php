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

// Prepare the SQL query to get the course roster for the faculty member
$sql = "SELECT cs.CourseId, cs.CourseName, s.StudentId, s.FirstName, s.LastName
        FROM CourseSection AS cs
        INNER JOIN Enrollment AS e ON cs.CourseSectionId = e.CourseSectionId
        INNER JOIN Student AS s ON e.StudentId = s.StudentId
        WHERE cs.FacultyId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $facultyId);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if any courses are found
if ($result->num_rows > 0) {
    $courseRoster = [];

    while ($row = $result->fetch_assoc()) {
        $courseRoster[] = [
            'CourseId' => $row['CourseId'],
            'CourseName' => $row['CourseName'],
            'StudentId' => $row['StudentId'],
            'FirstName' => $row['FirstName'],
            'LastName' => $row['LastName']
        ];
    }

    // Return the course roster as a JSON response
    echo json_encode([
        'status' => 'success',
        'courseRoster' => $courseRoster
    ]);
} else {
    // If no courses found for the faculty
    echo json_encode([
        'status' => 'error',
        'message' => 'No courses found for this faculty member.'
    ]);
}

exit();
?>
