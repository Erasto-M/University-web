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

    // Prepare the SQL query to fetch the degree audit for the specific student
    $sql = "SELECT s.FirstName, s.LastName, da.DegreeAuditId, da.AuditDetails
            FROM Student s
            JOIN DegreeAudit da ON s.StudentId = da.StudentId
            WHERE s.StudentId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the degree audit exists
    if ($result->num_rows > 0) {
        $degreeAudit = $result->fetch_assoc();

        // Return degree audit data in JSON format
        echo json_encode([
            'status' => 'success',
            'message' => 'Degree audit fetched successfully.',
            'data' => $degreeAudit
        ]);
    } else {
        // No degree audit found for this student
        echo json_encode([
            'status' => 'error',
            'message' => 'No degree audit found for this student.'
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
