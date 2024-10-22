<?php
//Updates counter for how many users are online
require 'avoid_errors.php';

// Confirms that logged in user is online
$updateLastLogin = time() + 10;
$sql = "UPDATE users SET last_login = $updateLastLogin WHERE user_id=" . $_SESSION['user_id'];
$conn->query($sql);

// Gets count of how many users are online
$sql = "SELECT COUNT(*) AS total FROM users WHERE last_login>" . time();
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);

// If there is one player online say "player", not players.
if ($row['total'] == 1) {
    $text = "1 player online";
} else {
    $text = $row['total'] . " players online. ";
}

echo $text . "<img class='live-count-icon' src='img/icons/live-count.svg' alt='live count icon'>";
?>