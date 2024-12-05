<?php
// Loads a count of players online and a button to see a list of them
require "../../php_scripts/avoid_errors.php";
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
} else {
    // Gets count of how many users are online
    $sql = "SELECT COUNT(*) AS total FROM users WHERE last_login>" . time();
    $result = $conn->query($sql);
    $row = mysqli_fetch_assoc($result);

    // If there is one player online say "player", not players.
    if ($row['total'] <= 0) {
        echo "Players online: None";
    } else {
        echo "Players online: " . $row['total'] . " <button onclick='showOnlineList()'>See List</button>";
    }
}