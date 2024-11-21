<?php
// Updates notification amount for new messages in hub
require "avoid_errors.php";
$totalNewMessages = 0;
// Check which chats the user is in
$stmt = $conn->prepare("SELECT * FROM chats WHERE user_id = ? ORDER BY last_chat DESC");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ((mysqli_num_rows($result) > 0)) {
    while ($row = mysqli_fetch_assoc($result)) {
        // If there are any unread messages, add the notification in a red circle
        $tablename = $row['tablename'];

        $conn -> select_db("gamehub_messages");
        $stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM $tablename WHERE unix_timestamp > ? AND user_id <> ?");
        $stmt2->bind_param("ss", $row['last_accessed'], $_SESSION['user_id']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $row2 = mysqli_fetch_assoc($result2);
        $totalNewMessages = $totalNewMessages + $row2['total'];

        $conn -> select_db("gamehub");
    }
}

if ($totalNewMessages > 0) {
    if ($totalNewMessages > 100) {
        $totalNewMessages = "99+";
    }
    echo "<div class='hub-notif-bubble'>$totalNewMessages</div>";
}