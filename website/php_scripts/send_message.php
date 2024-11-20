<?php
// Sends a message in the current table
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    require "compress.php";
    $message = htmlspecialchars($_POST['message-text']);
    $empty = false;

    // Check if input is empty
    if ($message === null || strlen($message) == 0 || ctype_space($message)) {
        // Input is empty
        $empty = true;
    }

    // Check if a chat has been selected
    if (!isset($_SESSION['current_table']) || $_SESSION['current_table'] == null) {
        echo "notselected";
        exit;
    }

    // Check if lenght of message exceeds 500 charachters
    if (strlen($message) > 500) {
        echo "toolong";
        exit;
    }

    // Check if media has been uploaded, if it has, attach it to message
    if ($_FILES['media-upload']['error'] == 4 || ($_FILES['media-upload']['size'] == 0 && $_FILES['media-upload']['error'] == 0)){
        $newfilename = NULL;
        if ($empty == true) {
            echo "empty";
            exit;
        }
    } else {
        $empty = false;
        define('MB', 1048576);
        if (ctype_space($message)) {
            $message = "";
        }
        $file_type = $_FILES['media-upload']['type'];
        $allowed = array("image/jpeg", "image/png", "image/gif", "image/webp");
        //If file is not jpeg, png, gif or webp
        if (!in_array($file_type, $allowed)) {
            echo "unsupported";
            exit;
        } else if ($_FILES['media-upload']['size'] > 3*MB) {
            // If file is over 3MB
            echo "toolarge";
            exit;
        } else {
            // Changes file name to avoid 2 files with same name
            $file_name = $_FILES['media-upload']['name'];
            $temp = explode(".", $file_name);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $folder = '../img/chat_images/'.$newfilename;

            // Uploads banner to server
            if (move_uploaded_file($_FILES['media-upload']['tmp_name'], $folder)) {
                // Compresses image if its not a gif, png or webp
                $dontcompress = array("image/png", "image/gif", "image/webp");
                if (!in_array($file_type, $dontcompress)) {
                    compress($folder, $folder, 80);
                }
            } else {
                echo "error";
                exit;
            };
        };
    }

    $reply = htmlspecialchars($_POST['reply']);
    $unix_timestamp = time();

    // Update last_chat column in chats table
    $current_table = $_SESSION['current_table'];
    $stmt = $conn->prepare("UPDATE chats SET last_chat = ? WHERE user_id = ? AND tablename = ?");
    $stmt->bind_param("sss", $unix_timestamp, $_SESSION['user_id'], $current_table );
    $stmt->execute();
    $stmt->close();

    // Get current time
    require "getdate.php";
    $timestamp = $date . " - " . $time;

    // Insert the message
    $conn -> select_db("gamehub_messages");
    $current_table = $_SESSION['current_table'];
    $stmt = $conn->prepare("INSERT INTO $current_table (user_id, message, timestamp, file, reply) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $_SESSION['user_id'], $message, $timestamp, $newfilename, $reply);
    $stmt->execute();
    $stmt->close();
    $conn -> select_db("gamehub");
} else {
    header("Location: ../index.php");
}