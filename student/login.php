<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (!$email || !$password) {
        echo json_encode(['error' => 'Email and password are required']);
        exit();
    }

    // Query to fetch user by email
    $sql = "SELECT * FROM AppUser WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(['error' => 'Database query error']);
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Compare passwords
        if ($password === $user['password']) { // Update to password_verify() if hashing is used
            $_SESSION['userId'] = $user['userID'];
            $_SESSION['email'] = $user['email'];

            // Redirect logic
            $redirectUrl = null;

            switch ($user['userType']) {
                case 'Student':
                    $redirectUrl = 'http://84.247.174.84/university/student/Frontend/dashboard.html';
                    break;
                case 'Faculty':
                    $redirectUrl = 'http://84.247.174.84/university/faculty/Frontend/faculty_dashboard.html';
                    break;
                case 'Admin':
                    $redirectUrl = 'http://84.247.174.84/university/admin/Frontend/admin_dashboard.html';
                    break;
                case 'Research':
                    $redirectUrl = 'http://84.247.174.84/university/research/Frontend/research_dashboard.html';
                    break;
                default:
                    echo json_encode(['error' => 'Invalid user type']);
                    exit();
            }

            echo json_encode(['redirect' => $redirectUrl]);
            exit();
        } else {
            echo json_encode(['error' => 'Invalid password']);
            exit();
        }
    } else {
        echo json_encode(['error' => 'Invalid email']);
        exit();
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}
?>
