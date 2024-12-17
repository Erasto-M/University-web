<?php
header("Access-Control-Allow-Origin: *");  // Allow all origins, or specify your React app's URL
header("Content-Type: application/json");  // Ensure the response is in JSON format
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Include your database connection file
include('db_connection.php');

session_start();  // Start the session to access session variables

// Check if the userId exists in session
if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['userId'];  // Retrieve userId from session

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query to get basic student info
    $query = "
        SELECT 
            au.firstName, 
            au.lastName, 
            au.email, 
            au.gender, 
            au.streetName,
            au.city,
            au.zipcode,
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
            'address' => $studentData['address'],
            'streetName' => $studentData['streetName'],
            'city' => $studentData['city'],
            'zipcode' => $studentData['zipcode']
        ]);
    } else {
        // Return an error message if no student data is found
        echo json_encode(['error' => 'Student data not found']);
    }

    $stmt->close();  // Close the prepared statement
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse the JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if all necessary fields are provided
    if (
        isset($data['firstName'], $data['lastName'], $data['email'], $data['streetName'], $data['city'], $data['zipcode'])
    ) {
        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $streetName = $data['streetName'];
        $city = $data['city'];
        $zipcode = $data['zipcode'];

        // Update query
        $updateQuery = "
            UPDATE AppUser 
            SET firstName = ?, lastName = ?, email = ?, streetName = ?, city = ?, zipcode = ?
            WHERE userId = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ssssssi", $firstName, $lastName, $email, $streetName, $city, $zipcode, $userId);

        if ($stmt->execute()) {
            echo json_encode(['message' => 'Profile updated successfully']);
        } else {
            echo json_encode(['error' => 'Failed to update profile']);
        }

        $stmt->close();  // Close the prepared statement
    } else {
        echo json_encode(['error' => 'Invalid input data']);
    }
}

$conn->close();  // Close the database connection
?>
