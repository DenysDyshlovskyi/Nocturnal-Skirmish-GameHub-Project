<?php
//Script for connecting to database

$servername = "localhost";
$username = "root";
$password = "NocSkir123!";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>