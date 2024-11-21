<?php
// Updates notification amount for new pending invites in hub
require "avoid_errors.php";

$sql = "SELECT COUNT(*) AS total FROM pending_friend_list WHERE user_id_2 = " . $_SESSION['user_id'];
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);

if ($row['total'] > 0) {
    echo "<div class='hub-notif-bubble'>" . $row['total'] . "</div>";
}
