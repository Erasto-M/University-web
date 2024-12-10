<?php
include 'config.php'; // Database connection

// Check if all necessary details are provided
if (isset($_POST['course_id']) && isset($_POST['faculty_id']) && isset($_POST['room_id']) && isset($_POST['available_seats']) && isset($_POST['semester_id'])) {
    $course_id = $_POST['course_id'];
    $faculty_id = $_POST['faculty_id'];
    $room_id = $_POST['room_id'];
    $available_seats = $_POST['available_seats'];
    $semester_id = $_POST['semester_id'];

    $sql = "INSERT INTO CourseSection (courseID, facultyID, roomID, availableSeats, semesterID)
            VALUES (?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii
