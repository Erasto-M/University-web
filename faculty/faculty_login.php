<?php

header("Access-Control-Allow-Origin: *");  // Allow all origins, or specify your React app's URL
header("Content-Type: application/json");  // Ensure the response is in JSON format

session_start();  // Start the session to store session variables

include 'db_connection.php';  // Include your database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data from POST request
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare the SQL query to check login using email and password
    $sql = "SELECT * FROM Faculty WHERE email = ? AND password = ?";  // Assuming the faculty table is called Faculty
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $password);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the faculty exists
    if ($result->num_rows > 0) {
        // Fetch faculty information
        $faculty = $result->fetch_assoc();

        // Set session variables
        $_SESSION['faculty_id'] = $faculty['faculty_id'];  // Store the faculty ID in the session
        $_SESSION['email'] = $faculty['email'];  // Store the email in session

        // Return the faculty info as a response
        echo json_encode(['success' => true, 'message' => 'Login successful']);
    } else {
        // Invalid credentials, set error message in session and redirect back to login page
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }

    exit();
}

?>
