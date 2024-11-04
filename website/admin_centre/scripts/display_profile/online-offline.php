<?php
require "../../../php_scripts/avoid_errors.php";
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../../admin_login.php?error=unauth");
} else {
    // Gets last login of user
    $stmt = $conn->prepare("SELECT last_login FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['displayprofile_userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if ($row['last_login'] > time()) {
        echo "User is online.";
    } else {
        echo "User is offline.";
    }
}