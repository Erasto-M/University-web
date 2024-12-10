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

    // Prepare the SQL query to fetch the student's hold information
    $sql = "SELECT h.HoldId, h.Description, h.HoldDate
            FROM Holds h
            WHERE h.StudentId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the student has any holds
    if ($result->num_rows > 0) {
        $holds = [];
        while ($row = $result->fetch_assoc()) {
            $holds[] = $row;
        }

        // Return the holds information in JSON format
        echo json_encode([
            'status' => 'success',
            'message' => 'Student holds fetched successfully.',
            'data' => $holds
        ]);
    } else {
        // No holds found for the student
        echo json_encode([
            'status' => 'error',
            'message' => 'No holds found for the provided student ID.'
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
