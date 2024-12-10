<?php
include 'config.php'; // Database connection

// Assuming course ID and updated details are provided
$course_id = $_POST['course_id'];
$new_course_name = $_POST['new_course_name'];
$new_description = $_POST['new_description'];
$new_num_of_credits = $_POST['new_num_of_credits'];

$sql = "UPDATE Course SET courseName = ?, description = ?, numOfCredits = ? WHERE courseID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssii", $new_course_name, $new_description, $new_num_of_credits, $course_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Course updated successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
