<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'vehicle management';

// Attempt to connect to MySQL
$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create DB if not exists
$sql = "CREATE DATABASE IF NOT EXISTS `$dbname`";
$conn->query($sql);

// Select DB
$conn->select_db($dbname);

// Function to start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
