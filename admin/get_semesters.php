<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include 'config.php';

try {
    $sql = "SELECT semesterID, semesterName FROM Semester";
    $result = $conn->query($sql);

    $semesters = [];
    while ($row = $result->fetch_assoc()) {
        $semesters[] = $row;
    }

    // Return valid JSON
    echo json_encode($semesters);
} catch (Exception $e) {
    error_log("Error fetching semesters: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Unable to fetch semesters.']);
}
?>
