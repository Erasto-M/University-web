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

$facultyId = $_SESSION['userId'];  // Get the faculty ID from session

// Query to fetch the advisees of the current faculty member
$sql = "SELECT s.StudentId, s.FirstName, s.LastName, s.DOB, a.AdvisorId
        FROM Student s
        JOIN Advisor a ON s.StudentId = a.StudentId
        WHERE a.FacultyId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $facultyId);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any advisees
$advisees = [];
while ($row = $result->fetch_assoc()) {
    $advisees[] = $row;  // Store each advisee in an array
}

// Return advisee data in JSON format
echo json_encode([
    'status' => 'success',
    'message' => 'Advisees fetched successfully.',
    'data' => $advisees
]);
exit();
?>
