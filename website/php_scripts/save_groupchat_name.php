<?php
//Saves new groupchat name to database
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $groupchat_name = htmlspecialchars($_POST['groupchatName']);

    // Check if the current chat is a groupchat and if the user has access
    if (isset($_SESSION['current_table'])) {
        // Does the user have access to the chat?
        $stmt = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND user_id = ?");
        $stmt->bind_param("ss", $_SESSION['current_table'], $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0){
            echo "error";
            exit;
        }
        $stmt->close();

        // Is the chat a groupchat?
        $stmt = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND type = 'groupchat'");
        $stmt->bind_param("s", $_SESSION['current_table']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0){
            echo "error";
            exit;
        }
        $stmt->close();

        // Check if input is empty
        if ($groupchat_name === null || strlen($groupchat_name) == 0 || ctype_space($groupchat_name)) {
            // Input is empty
            echo "empty";
            exit;
        }

        // Check if lenght of groupchat name exceeds 28 charachters
        if (strlen($groupchat_name) > 28) {
            echo "too_long";
            exit;
        }

        // Update groupchat name
        $stmt = $conn->prepare("UPDATE groupchat_settings SET groupchat_name = ? WHERE tablename = ?");
        $stmt->bind_param("ss", $groupchat_name, $_SESSION['current_table']);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "error";
        exit;
    }
} else {
    header("Location: ../index.php");
};