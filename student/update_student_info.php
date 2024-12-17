<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

include('db_connection.php');
session_start();

if (!isset($_SESSION['userId'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
$field = $data['field'] ?? null;
$value = $data['value'] ?? null;

if (!$field || !$value) {
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

$validFields = ['name', 'email', 'gender', 'dob', 'address', 'zipcode', 'streetName', 'city'];
if (!in_array($field, $validFields)) {
    echo json_encode(['error' => 'Invalid field']);
    exit();
}

$userId = $_SESSION['userId'];

$fieldMap = [
    'name' => 'firstName',
    'email' => 'email',
    'gender' => 'gender',
    'dob' => 'DOB',
    'address' => 'houseNo',
    'zipcode' => 'zipcode',
    'streetName' => 'streetName',
    'city' => 'city',
];

$column = $fieldMap[$field];

$query = "UPDATE AppUser SET $column = ? WHERE userId = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $value, $userId);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Database update failed']);
}

$stmt->close();
$conn->close();
?>
