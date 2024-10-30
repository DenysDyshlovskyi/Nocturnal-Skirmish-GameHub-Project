<?php
// Gets total amount of friend request the user has pending
require "avoid_errors.php";

$sql = "SELECT COUNT(*) AS total FROM pending_friend_list WHERE user_id_2 = " . $_SESSION['user_id'];
$result = $conn->query($sql);
$row = mysqli_fetch_assoc($result);

if ($row['total'] > 0) {
    echo $row['total'];
} else {
    echo "none";
}