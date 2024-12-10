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

// Check if the necessary POST data is provided
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['oldPassword']) && isset($_POST['newPassword']) && isset($_POST['confirmPassword'])) {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validate password criteria
    if (strlen($newPassword) < 8) {
        echo json_encode([
            'status' => 'error',
            'message' => 'New password must be at least 8 characters long.'
        ]);
        exit();
    }

    if ($newPassword !== $confirmPassword) {
        echo json_encode([
            'status' => 'error',
            'message' => 'New password and confirm password do not match.'
        ]);
        exit();
    }

    // Fetch the current password for the logged-in faculty member
    $sql = "SELECT Password FROM AppUser WHERE UserId = (SELECT UserId FROM Faculty WHERE FacultyId = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $facultyId);
    $stmt->execute();
    $result = $stmt->get_result();

    // If faculty member not found or password does not match
    if ($result->num_rows === 0) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Faculty member not found or invalid credentials.'
        ]);
        exit();
    }

    $user = $result->fetch_assoc();
    $hashedPassword = $user['Password'];

    // Check if the old password matches the stored password
    if (!password_verify($oldPassword, $hashedPassword)) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Old password is incorrect.'
        ]);
        exit();
    }

    // Hash the new password before storing it
    $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update the password in the database
    $sql = "UPDATE AppUser SET Password = ? WHERE UserId = (SELECT UserId FROM Faculty WHERE FacultyId = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newHashedPassword, $facultyId);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Password changed successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to change password. Please try again later.'
        ]);
    }
    exit();
} else {
    // If POST data is missing or invalid
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request. Please provide old password, new password, and confirm password.'
    ]);
    exit();
}
?>
