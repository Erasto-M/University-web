<?php
include 'config.php'; // Database connection

// Check if required fields are provided
if (isset($_POST['course_name']) && isset($_POST['faculty_id']) && isset($_POST['semester_id']) && isset($_POST['room_id'])) {
    $course_name = $_POST['course_name'];
    $faculty_id = $_POST['faculty_id'];
    $semester_id = $_POST['semester_id'];
    $room_id = $_POST['room_id'];
    $section_no = $_POST['section_no']; // Optional: section number for the course
    $available_seats = $_POST['available_seats'];

    // Insert new course section into the master schedule
    $sql = "INSERT INTO CourseSection (courseID, sectionNo, facultyID, timeSlot, roomID, availableSeats, semesterID)
            SELECT courseID, ?, ?, ?, ?, ?, ?
            FROM Course WHERE courseName = ?";

    // Prepare statement
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiis", $section_no, $faculty_id, $room_id, $available_seats, $semester_id, $course_name);
    
    // Execute query
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Course section created successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Missing required parameters"]);
}

$conn->close();
?>
