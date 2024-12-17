<?php
// Database Connection
$servername = "84.247.174.84";
$username = "ecoville"; // Your database username
$password = "ecoville"; // Your database password// Your database password
$dbname = "universitydb"; 

$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check for CRN
if (isset($_GET['crnNo'])) {
    $crnNo = intval($_GET['crnNo']);

    // SQL Query to get registered students, courses, and grades
    $sql = "
        SELECT U.userID, CONCAT(U.firstName, ' ', U.lastName) AS studentName, 
               C.courseName, E.grade
        FROM Enrollment AS E
        JOIN AppUser AS U ON E.studentID = U.userID
        JOIN CourseSection AS CS ON E.crnNo = CS.crnNo
        JOIN Course AS C ON CS.courseID = C.courseID
        WHERE E.crnNo = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $crnNo);
    $stmt->execute();
    $result = $stmt->get_result();

    $roster = [];
    while ($row = $result->fetch_assoc()) {
        $roster[] = $row;
    }

    if (count($roster) > 0) {
        echo json_encode(["status" => "success", "roster" => $roster]);
    } else {
        echo json_encode(["status" => "error", "message" => "No students found for this section."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "CRN number is missing."]);
}

$conn->close();
?>
