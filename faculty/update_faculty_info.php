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

// Check if POST request contains the necessary information
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the updated faculty information from the POST request
    $facultyId = $_SESSION['userId'];  // Assuming facultyId is stored in session as userId
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];

    // Check if the inputs are not empty
    if (empty($firstName) || empty($lastName)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'First name and Last name are required.'
        ]);
        exit();
    }

    // Prepare the SQL query to update the faculty's personal information
    $sql = "UPDATE Faculty SET FirstName = ?, LastName = ? WHERE FacultyId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $firstName, $lastName, $facultyId);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Faculty information updated successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Error updating faculty information. Please try again.'
        ]);
    }
} else {
    // If the request method is not POST, return an error
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method.'
    ]);
}

exit();
?>
