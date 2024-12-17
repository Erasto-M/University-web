<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include('db_connection.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Database connection configuration
$servername = "84.247.174.84";
$username = "ecoville"; // Your database username
$password = "ecoville"; // Your database password// Your database password
$dbname = "universitydb"; Update as per your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

/**
 * Retrieve advisees for a specific faculty for "View Advisement" use case.
 */
function getAdvisees($facultyID) {
    global $pdo;
    $sql = "SELECT AppUser.userID AS studentId, AppUser.firstName, AppUser.lastName, AppUser.email, Advisor.dateOfAppointment 
            FROM Advisor 
            JOIN AppUser ON Advisor.studentID = AppUser.userID 
            WHERE Advisor.facultyID = :facultyID";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['facultyID' => $facultyID]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retrieve all advisors for "View All Advisors" use case.
 */
function getAllAdvisors() {
    global $pdo;
    $sql = "SELECT AppUser.userID AS facultyId, AppUser.firstName, AppUser.lastName, AppUser.email 
            FROM Advisor 
            JOIN AppUser ON Advisor.facultyID = AppUser.userID";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Retrieve all advisees for "View All Advisees" use case.
 */
function getAllAdvisees() {
    global $pdo;
    $sql = "SELECT AppUser.userID AS studentId, AppUser.firstName, AppUser.lastName, AppUser.email 
            FROM Advisor 
            JOIN AppUser ON Advisor.studentID = AppUser.userID";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Simulate faculty login (for demonstration purposes, use session-based faculty ID)
$facultyID = $_SESSION['userId']; // Use dynamic session-based user ID

// Retrieve Advisees
$advisees = getAdvisees($facultyID);

// Retrieve All Advisors
$allAdvisors = getAllAdvisors();

// Retrieve All Advisees
$allAdvisees = getAllAdvisees();

// Return all data as JSON
echo json_encode([
    'advisees' => $advisees,
    'allAdvisors' => $allAdvisors,
    'allAdvisees' => $allAdvisees
]);
