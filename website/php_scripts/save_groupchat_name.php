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

        // Check if old groupchat name is diffrent from the new name
        $groupchat_name = trim($groupchat_name);
        $stmt = $conn->prepare("SELECT groupchat_name FROM groupchat_settings WHERE tablename = ?");
        $stmt->bind_param("s", $_SESSION['current_table']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        if ($row['groupchat_name'] == $groupchat_name) {
            echo "same";
            exit;
        }
        $stmt->close();

        // Update groupchat name
        $stmt = $conn->prepare("UPDATE groupchat_settings SET groupchat_name = ? WHERE tablename = ?");
        $stmt->bind_param("ss", $groupchat_name, $_SESSION['current_table']);
        $stmt->execute();
        $stmt->close();

        // Get current time
        require "getdate.php";
        $timestamp = $date . " - " . $time;
        $unix_timestamp = time();

        // Insert notifier message into groupchat that says that the user changed the groupchat name
        $tablename = $_SESSION['current_table'];
        $message = $timestamp . " | " . $_SESSION['user_profile_nickname'] . " changed the groupchat name to '$groupchat_name'";
        $notifier_userid = 0;
        $conn -> select_db("gamehub_messages");
        $stmt = $conn->prepare("INSERT INTO $tablename (user_id, message, timestamp, unix_timestamp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $notifier_userid, $message, $timestamp, $unix_timestamp);
        $stmt->execute();
        $stmt->close();
        $conn -> select_db("gamehub");
    } else {
        echo "error";
        exit;
    }
} else {
    header("Location: ../index.php");
};