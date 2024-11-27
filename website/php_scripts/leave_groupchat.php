<?php
// Leaves groupchat
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";

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

        // Deletes row in chats that is tied to the groupchat
        $stmt = $conn->prepare("DELETE FROM chats WHERE user_id = ? AND tablename = ?");
        $stmt->bind_param("ss", $_SESSION['user_id'], $_SESSION['current_table']);
        $stmt->execute();
        $stmt->close();

        // Get current time
        require "getdate.php";
        $timestamp = $date . " - " . $time;
        $unix_timestamp = time();

        // Insert notifier message into groupchat that says that the user left the chat
        $tablename = $_SESSION['current_table'];
        $message = $timestamp . " | " . $_SESSION['user_profile_nickname'] . " has left the groupchat.";
        $notifier_userid = 0;
        $conn -> select_db("gamehub_messages");
        $stmt = $conn->prepare("INSERT INTO $tablename (user_id, message, timestamp, unix_timestamp) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $notifier_userid, $message, $timestamp, $unix_timestamp);
        $stmt->execute();
        $stmt->close();
        $conn -> select_db("gamehub");

        // If there are no more people in the groupchat, move the chat to an archive
        $stmt = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND type = 'groupchat'");
        $stmt->bind_param("s", $tablename);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0){
            // Moves the chat table into archived chats

            // Create table to move the chat into
            $conn -> select_db("gamehub_messages_archive");
            $new_tablename = $tablename . "_archive";
            $stmt1 = $conn->prepare("CREATE TABLE $new_tablename (message_id int NOT NULL AUTO_INCREMENT, user_id int, message varchar(500), file varchar(50), timestamp varchar(64), edited int DEFAULT 0, reply int DEFAULT 0, PRIMARY KEY (message_id));");
            $stmt1->execute();
            $stmt1->close();

            // Move chat into table
            $conn -> select_db("gamehub_messages");
            $stmt1 = $conn->prepare("SELECT * FROM $tablename");
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            if ((mysqli_num_rows($result1) > 0)) {
                while ($row1 = mysqli_fetch_assoc($result1)) {
                    $conn -> select_db("gamehub_messages_archive");
                    $stmt1 = $conn->prepare("INSERT INTO $new_tablename (user_id, message, file, timestamp, edited, reply) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt1->bind_param("ssssss", $row1['user_id'], $row1['message'], $row1['file'], $row1['timestamp'], $row1['edited'], $row1['reply']);
                    $stmt1->execute();
                }
            }
            $stmt1->close();

            // Delete old table
            $conn -> select_db("gamehub_messages");
            $stmt1 = $conn->prepare("DROP TABLE $tablename");
            $stmt1->execute();
            $stmt1->close();

            $conn -> select_db("gamehub");
        }
        $stmt->close();

        // Changes current messenger to the most recent chat
        // Get the last chat you sent message in, and set that to the current chat
        $stmt3 = $conn->prepare("SELECT * FROM chats WHERE user_id = ? ORDER BY last_chat DESC LIMIT 1");
        $stmt3->bind_param("s", $_SESSION['user_id']);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        if ((mysqli_num_rows($result3) <= 0)) {
            $stmt3->close();
            unset($_SESSION['current_messenger']);
            unset($_SESSION['current_messenger_type']);
            unset($_SESSION['current_table']);
            goto end;
        }
        $row3 = mysqli_fetch_assoc($result3);
        $_SESSION['current_table'] = $row3['tablename'];

        $type = "";
        if ($row3['type'] == "groupchat") {
            $type = "groupchat";
        } else {
            $type = "two_user";
        }
        $stmt3->close();

        // Set the current messenger
        if ($type == "two_user") {
            $stmt3 = $conn->prepare("SELECT user_id FROM chats WHERE user_id <> ? AND tablename = ?");
            $stmt3->bind_param("ss", $_SESSION['user_id'], $row3['tablename']);
            $stmt3->execute();
            $result3 = $stmt3->get_result();
            $row3 = mysqli_fetch_assoc($result3);

            $_SESSION['current_messenger'] = $row3['user_id'];
            $_SESSION['current_messenger_type'] = "two_user";
            $stmt3->close();
        } else if ($type == "groupchat") {
            $_SESSION['current_messenger'] = $row3['tablename'];
            $_SESSION['current_messenger_type'] = "groupchat";
        }

        end:
    } else {
        echo "error";
        exit;
    }
} else {
    header("Location: ../index.php");
}