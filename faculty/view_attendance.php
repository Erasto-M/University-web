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

    // SQL Query to count attendance per student
    $sql = "
        SELECT U.userID, CONCAT(U.firstName, ' ', U.lastName) AS studentName, 
               COUNT(A.present) AS attendedClasses
        FROM Attendance AS A
        JOIN AppUser AS U ON A.studentID = U.userID
        WHERE A.crnNo = ? AND A.present = 1
        GROUP BY A.studentID
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $crnNo);
    $stmt->execute();
    $result = $stmt->get_result();

    $attendance = [];
    while ($row = $result->fetch_assoc()) {
        $attendance[] = $row;
    }

    if (count($attendance) > 0) {
        echo json_encode(["status" => "success", "attendance" => $attendance]);
    } else {
        echo json_encode(["status" => "error", "message" => "No attendance records found for this section."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "CRN number is missing."]);
}

$conn->close();
?>
