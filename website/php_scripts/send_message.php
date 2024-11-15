<?php
// Sends a message in the current table
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $message = htmlspecialchars($_POST['message']);

    // Check if input is empty
    if ($message === null || strlen($message) == 0) {
        // Input is empty
        echo "empty";
        exit;
    }

    // Get current time
    require "getdate.php";
    $timestamp = $date . " - " . $time;

    // Insert the message
    $conn -> select_db("gamehub_messages");
    $current_table = $_SESSION['current_table'];
    $stmt = $conn->prepare("INSERT INTO $current_table (user_id, message, timestamp) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $_SESSION['user_id'], $message, $timestamp);
    $stmt->execute();
    $stmt->close();
    $conn -> select_db("gamehub");
} else {
    header("Location: ../index.php");
}