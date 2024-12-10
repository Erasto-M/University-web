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

// Query to fetch all advisors and their associated faculties
$sql = "SELECT f.FacultyId, f.FirstName AS FacultyFirstName, f.LastName AS FacultyLastName, a.AdvisorId
        FROM Faculty f
        JOIN Advisor a ON f.FacultyId = a.FacultyId";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any advisors
$advisors = [];
while ($row = $result->fetch_assoc()) {
    $advisors[] = $row;  // Store each advisor in an array
}

// Return advisors data in JSON format
echo json_encode([
    'status' => 'success',
    'message' => 'Advisors fetched successfully.',
    'data' => $advisors
]);
exit();
?>
