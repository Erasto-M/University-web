<?php
header("Access-Control-Allow-Origin: *");  // Allow all origins, or specify your app's URL
header("Content-Type: application/json");  // Ensure the response is in JSON format

session_start();  // Start the session to access session variables

include 'db_connection.php';  // Include your database connection file

try {
    // Query to fetch all advisors with details from AppUser and Faculty tables
    $sql = "
        SELECT 
            a.facultyID AS advisorFacultyID,
            au.userID AS userID,
            au.firstName AS firstName,
            au.lastName AS lastName,
            au.email AS email,
            f.specialty AS specialty,
            f.rank AS rank,
            f.facultyType AS facultyType
        FROM Advisor a
        JOIN Faculty f ON a.facultyID = f.facultyID
        JOIN AppUser au ON f.facultyID = au.userID
        ORDER BY au.lastName, au.firstName";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are any advisors
    $advisors = [];
    while ($row = $result->fetch_assoc()) {
        $advisors[] = [
            'facultyID' => $row['advisorFacultyID'],
            'userID' => $row['userID'],
            'firstName' => $row['firstName'],
            'lastName' => $row['lastName'],
            'email' => $row['email'],
            'specialty' => $row['specialty'],
            'rank' => $row['rank'],
            'facultyType' => $row['facultyType']
        ];
    }

    // Return advisors data in JSON format
    echo json_encode([
        'status' => 'success',
        'message' => 'All advisors fetched successfully.',
        'data' => $advisors
    ]);
} catch (Exception $e) {
    // Handle errors and return an error message
    echo json_encode([
        'status' => 'error',
        'message' => 'Error fetching advisors: ' . $e->getMessage()
    ]);
}

exit();
?>
