<?php
// Creates a chat
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";

    $posted_userid = htmlspecialchars($_POST['user_id']);

    // Check if user id is in friend list
    $stmt = $conn->prepare("SELECT * FROM friend_list WHERE user_id_2 = ? AND user_id_1 = ?");
    $stmt->bind_param("ss", $posted_userid, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows <= 0){
        echo "error";
        exit;
    };
    $stmt->close();

    // Check if a chat with friend has alredy been made
    $stmt = $conn->prepare("SELECT * FROM chats WHERE type = 'two_user' AND user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows != 0){
        while ($row = mysqli_fetch_assoc($result)) {
            $stmt2 = $conn->prepare("SELECT user_id FROM chats WHERE tablename = ? AND user_id <> ?");
            $stmt2->bind_param("ss", $row['tablename'],$_SESSION['user_id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = mysqli_fetch_assoc($result2);
            if ($row2['user_id'] == $posted_userid) {
                $_SESSION['current_table'] = $row['tablename'];
                $_SESSION['current_messenger'] = $posted_userid;
                exit;
            }
            $stmt2->close();
        }
    };
    $stmt->close();

    // Generate random name for chat table
    $tablename = bin2hex(random_bytes(10));

    // Create chat table in gamehub_messages database
    $conn -> select_db("gamehub_messages");
    $stmt = $conn->prepare("CREATE TABLE $tablename (message_id int NOT NULL AUTO_INCREMENT, user_id int, message varchar(500), file varchar(50), timestamp varchar(64), edited int DEFAULT 0, reply int DEFAULT 0, unix_timestamp int NOT NULL DEFAULT 0, PRIMARY KEY (message_id));");
    $stmt->execute();
    $stmt->close();
    $conn -> select_db("gamehub");

    // Give access/register user id and friends user id to the new table.

    // Logged in user
    $stmt = $conn->prepare("INSERT INTO chats (user_id, tablename, type) VALUES (?, ?, 'two_user')");
    $stmt->bind_param("ss", $_SESSION['user_id'], $tablename);
    $stmt->execute();
    $stmt->close();

    // Friend
    $stmt = $conn->prepare("INSERT INTO chats (user_id, tablename, type) VALUES (?, ?, 'two_user')");
    $stmt->bind_param("ss", $posted_userid, $tablename);
    $stmt->execute();
    $stmt->close();

    $_SESSION['current_table'] = $tablename;
    $_SESSION['current_messenger'] = $posted_userid;
    $_SESSION['current_messenger_type'] = "two_user";
} else {
    header("Location: ../index.php");
}