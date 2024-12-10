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

// Prepare the SQL query to get the master schedule for the faculty member
$sql = "SELECT cs.CourseId, cs.CourseName, cs.DayOfWeek, cs.StartTime, cs.EndTime, r.RoomNumber, b.Name AS BuildingName
        FROM CourseSection AS cs
        INNER JOIN Room AS r ON cs.RoomId = r.RoomId
        INNER JOIN Building AS b ON r.BuildingId = b.BuildingId
        WHERE cs.FacultyId = ?
        ORDER BY cs.DayOfWeek, cs.StartTime";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $facultyId);

// Execute the query
$stmt->execute();
$result = $stmt->get_result();

// Check if any schedule is found
if ($result->num_rows > 0) {
    $masterSchedule = [];

    while ($row = $result->fetch_assoc()) {
        $masterSchedule[] = [
            'CourseId' => $row['CourseId'],
            'CourseName' => $row['CourseName'],
            'DayOfWeek' => $row['DayOfWeek'],
            'StartTime' => $row['StartTime'],
            'EndTime' => $row['EndTime'],
            'RoomNumber' => $row['RoomNumber'],
            'BuildingName' => $row['BuildingName']
        ];
    }

    // Return the master schedule as a JSON response
    echo json_encode([
        'status' => 'success',
        'masterSchedule' => $masterSchedule
    ]);
} else {
    // If no schedule found for the faculty
    echo json_encode([
        'status' => 'error',
        'message' => 'No courses scheduled for this faculty member.'
    ]);
}

exit();
?>
