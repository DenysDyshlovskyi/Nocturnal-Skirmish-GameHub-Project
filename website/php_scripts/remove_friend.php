<?php
// Removes friend from friends list
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $user_id = htmlspecialchars($_POST['user_id']);

    // Removes friend from logged in users friend list
    $stmt = $conn->prepare("DELETE FROM friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
    $stmt->bind_param("ss", $_SESSION['user_id'] ,$user_id);
    $stmt->execute();
    $stmt->close();

    // Removes logged in user from friends friend list
    $stmt = $conn->prepare("DELETE FROM friend_list WHERE user_id_2 = ? AND user_id_1 = ?");
    $stmt->bind_param("ss", $_SESSION['user_id'] ,$user_id);
    $stmt->execute();
    $stmt->close();

    // Removes chat with friend if they had a chat
    $stmt = $conn->prepare("SELECT tablename FROM chats WHERE user_id = ? AND type = 'two_user'");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) > 0)) {
        while ($row = mysqli_fetch_assoc($result)) {
            $stmt1 = $conn->prepare("SELECT user_id FROM chats WHERE tablename = ? AND user_id <> ?");
            $stmt1->bind_param("ss", $row['tablename'], $_SESSION['user_id']);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $row1 = mysqli_fetch_assoc($result1);
            if ($row1['user_id'] == $user_id) {
                $stmt2 = $conn->prepare("DELETE FROM chats WHERE tablename = ?");
                $stmt2->bind_param("s", $row['tablename']);
                $stmt2->execute();
                $tablename = $row['tablename'];
                $stmt2->close();
            }
            $stmt1->close();
        }
        // Unsets current table if the table is with the friend
        if (isset($_SESSION['current_table'])) {
            if ($_SESSION['current_table'] == $tablename) {
                unset($_SESSION['current_table']);
            }
        }

        // Unsets current messenger if messenger is friend
        if (isset($_SESSION['current_messenger'])) {
            if ($_SESSION['current_messenger'] == $user_id) {
                unset($_SESSION['current_messenger']);
            }
        }

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

    //Passes user id of friend to remove to JavaScript
    echo $user_id;
} else {
    header("Location: ../index.php");
}