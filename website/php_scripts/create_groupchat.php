<?php
// Creates groupchat
require "avoid_errors.php";

// if checkboxes arent set
if (!isset($_POST['create-groupchat-checkbox'])) {
    echo "empty";
    exit;
}

// if less than 2 checkboxes were checked
$count = count($_POST['create-groupchat-checkbox']);
if ($count < 2) {
    echo "short";
    exit;
}

// Prevent XSS for each checkbox value
foreach ($_POST['create-groupchat-checkbox'] as $user_id) {
    $user_id = htmlspecialchars($user_id);

    // Check if user id actually exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ((mysqli_num_rows($result) <= 0)) {
        echo "error";
        exit;
    };
    $stmt->close();

    // Check if user is actually in friends list
    $stmt = $conn->prepare("SELECT user_id_2 FROM friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
    $stmt->bind_param("ss", $_SESSION['user_id'], $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "error";
        exit;
    }
    $stmt->close();
}

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
$stmt = $conn->prepare("INSERT INTO chats (user_id, tablename, type) VALUES (?, ?, 'groupchat')");
$stmt->bind_param("ss", $_SESSION['user_id'], $tablename);
$stmt->execute();
$stmt->close();

// Everyone else
foreach ($_POST['create-groupchat-checkbox'] as $user_id) {
    $user_id = htmlspecialchars($user_id);
    $stmt = $conn->prepare("INSERT INTO chats (user_id, tablename, type) VALUES (?, ?, 'groupchat')");
    $stmt->bind_param("ss", $user_id, $tablename);
    $stmt->execute();
    $stmt->close();
}