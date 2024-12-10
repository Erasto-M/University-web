<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

session_start();
session_destroy();
header('Location: http://84.247.174.84/university/student/Frontend/login.html'); // Redirect to login page
exit();
?>
