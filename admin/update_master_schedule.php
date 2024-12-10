<?php
include 'config.php'; // Database connection

// Check if course section ID and new details are provided
if (isset($_POST['crn_no']) && isset($_POST['new_faculty_id']) && isset($_POST['new_room_id']) && isset($_POST['new_available_seats'])) {
    $crn_no = $_POST['crn_no'];
    $new_faculty_id = $_POST['new_faculty_id'];
    $new_room_id = $_POST['new_room_id'];
    $new_available_seats = $_POST['new_available_seats'];

    // Update course section in the master schedule
    $sql = "UPDATE CourseSection SET facultyID = ?, roomID = ?, availableSeats = ? WHERE crnNo = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $new_faculty_id, $new_room_id, $new_available_seats, $crn_no);
    
    // Execute query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Course section updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required parameters"]);
}

$conn->close();
?>
