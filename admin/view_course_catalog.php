<?php
include 'config.php'; // Database connection

$sql = "SELECT * FROM Course";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    echo json_encode(["status" => "success", "data" => $courses]);
} else {
    echo json_encode(["status" => "error", "message" => "No courses available"]);
}

$conn->close();
?>
