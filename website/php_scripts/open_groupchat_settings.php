<?php
// Prepares opening of groupchat settings
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $groupchat = htmlspecialchars($_POST['groupchat']);

    // Check if the current chat is a groupchat and if the user has access
    if (isset($_SESSION['current_table'])) {
        // Does the user have access to the chat?
        $stmt = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND user_id = ?");
        $stmt->bind_param("ss", $groupchat, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0){
            echo "error";
            exit;
        }
        $stmt->close();

        // Is the chat a groupchat?
        $stmt = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND type = 'groupchat'");
        $stmt->bind_param("s", $groupchat);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0){
            echo "not_groupchat";
            exit;
        }
        $stmt->close();
    } else {
        echo "error";
        exit;
    }
} else {
    header("Location: ../index.php");
}