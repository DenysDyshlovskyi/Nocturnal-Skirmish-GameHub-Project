<?php
// Sends friend request to posted user id
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    require "getdate.php";
    $user_id = htmlspecialchars($_POST['user_id']);

    $stmt = $conn->prepare("INSERT INTO pending_friend_list (user_id_1, user_id_2, sent) VALUES (?,?,?)");
    $stmt->bind_param("sss", $_SESSION['user_id'], $user_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $stmt->close();
} else {
    header("Location: ../../index.php");
}