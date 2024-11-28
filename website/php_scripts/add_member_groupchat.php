<?php
// Adds new members to groupchat
require "avoid_errors.php";

// if checkboxes arent set
if (!isset($_POST['create-groupchat-checkbox'])) {
    echo "empty";
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

$notifier_nicknames = "";
$tablename = $_SESSION['current_table'];

// Insert users into chat
foreach ($_POST['create-groupchat-checkbox'] as $user_id) {
    $user_id = htmlspecialchars($user_id);
    $stmt = $conn->prepare("INSERT INTO chats (user_id, tablename, type) VALUES (?, ?, 'groupchat')");
    $stmt->bind_param("ss", $user_id, $tablename);
    $stmt->execute();
    $stmt->close();

    // Get nickname of every person for notifier message
    $stmt = $conn->prepare("SELECT nickname FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $notifier_nicknames = $notifier_nicknames . ", " . $row['nickname'];
    $stmt->close();
}

$notifier_nicknames = ltrim($notifier_nicknames, $notifier_nicknames[0]);

// Get current time
require "getdate.php";
$timestamp = $date . " - " . $time;
$unix_timestamp = time();

// Insert notifier message into groupchat that says that a members were added
$message = "$timestamp | " . $_SESSION['user_profile_nickname'] . " added $notifier_nicknames to groupchat.";
$notifier_userid = 0;
$conn -> select_db("gamehub_messages");
$stmt = $conn->prepare("INSERT INTO $tablename (user_id, message, timestamp, unix_timestamp) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $notifier_userid, $message, $timestamp, $unix_timestamp);
$stmt->execute();
$stmt->close();
$conn -> select_db("gamehub");