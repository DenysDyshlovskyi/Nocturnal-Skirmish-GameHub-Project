<?php
require "avoid_errors.php";
// Gets information for ui for confirming editing message
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $message_id = htmlspecialchars($_POST['message_id']);
    $tablename = $_SESSION['current_table'];

    // Check if message that user is trying to edit was actually sent by them
    $conn -> select_db("gamehub_messages");
    $stmt = $conn->prepare("SELECT * FROM $tablename WHERE message_id = ? AND user_id = ?");
    $stmt->bind_param("ss", $message_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0){
        // User didnt send the message or message doesnt exist
        echo "error";
        $stmt->close();
        exit;
    }

    // Remember message id for later.
    $_SESSION['editmessage_id'] = $message_id;

    $stmt->close();
    $conn -> select_db("gamehub");
} else {
    header("Location: ../index.php");
}