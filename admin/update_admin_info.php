<?php
include 'config.php'; // Database connection

// Assuming the admin ID and updated details are provided
$admin_id = $_POST['admin_id'];
$new_first_name = $_POST['new_first_name'];
$new_last_name = $_POST['new_last_name'];
$new_email = $_POST['new_email'];

$sql = "UPDATE AppUser SET firstName = ?, lastName = ?, email = ? WHERE userID = ? AND userType = 'Admin'";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $new_first_name, $new_last_name, $new_email, $admin_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Personal information updated"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
