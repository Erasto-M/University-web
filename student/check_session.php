<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

session_start();

if (isset($_SESSION['userId'])) {
    echo json_encode(['loggedIn' => true]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>
