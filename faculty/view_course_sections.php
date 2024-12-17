<?php
// Start session and include database connection
session_start();
header('Content-Type: application/json'); // Return JSON response

// Database Configuration
$servername = "84.247.174.84";
$username = "ecoville"; // Your database username
$password = "ecoville"; // Your database password// Your database password
$dbname = "universitydb"; 

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL query to fetch semester details
    $sql = "
        SELECT 
            semesterName,
            semesterYear,
            startTime,
            endTime,
            DATEDIFF(endTime, startTime) AS duration
        FROM Semester
    ";

    // Prepare and execute the query
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // Fetch all semester details
    $semesters = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    echo json_encode($semesters);

} catch (PDOException $e) {
    // Handle database errors
    echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
    exit();
}
?>
