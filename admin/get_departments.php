<?php
include 'config.php';

$sql = "SELECT deptID, deptName FROM Department";
$result = $conn->query($sql);

$departments = [];
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

echo json_encode($departments);
?>
