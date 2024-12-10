<?php
header("Access-Control-Allow-Origin: *");  // Allow all origins, or specify your React app's URL
header("Content-Type: application/json");  // Ensure the response is in JSON format

include('db_connection.php');  // Include your database connection file

session_start();  // Start the session to access session variables

// Check if the userId exists in session
if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['userId'];  // Retrieve userId from session

// Query to get basic student info
$query = "
    SELECT 
        au.firstName, 
        au.lastName, 
        au.email, 
        au.gender, 
        au.DOB AS dateOfBirth,
        au.houseNo AS address, 
        s.studentID AS registrationNumber
    FROM AppUser au
    INNER JOIN Student s ON au.userId = s.studentID
    WHERE au.userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $userId);  // Bind the userId as an integer
$stmt->execute();
$result = $stmt->get_result();

// Check if any rows were returned
if ($result->num_rows > 0) {
    $studentData = $result->fetch_assoc();  // Fetch the data as an associative array
    // Return the data in JSON format
    echo json_encode([
        'registrationNumber' => $studentData['registrationNumber'],
        'name' => $studentData['firstName'] . ' ' . $studentData['lastName'],
        'email' => $studentData['email'],
        'gender' => $studentData['gender'],
        'dateOfBirth' => $studentData['dateOfBirth'],
        'address' => $studentData['address']
    ]);
} else {
    // Return an error message if no student data is found
    echo json_encode(['error' => 'Student data not found']);
}

$stmt->close();  // Close the prepared statement
$conn->close();  // Close the database connection
?>
