<?php
// Removes friend from friends list
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $user_id = htmlspecialchars($_POST['user_id']);

    // Removes friend from logged in users friend list
    $stmt = $conn->prepare("DELETE FROM friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
    $stmt->bind_param("ss", $_SESSION['user_id'] ,$user_id);
    $stmt->execute();
    $stmt->close();

    // Removes logged in user from friends friend list
    $stmt = $conn->prepare("DELETE FROM friend_list WHERE user_id_2 = ? AND user_id_1 = ?");
    $stmt->bind_param("ss", $_SESSION['user_id'] ,$user_id);
    $stmt->execute();
    $stmt->close();

    //Passes user id of friend to remove to JavaScript
    echo $user_id;
} else {
    header("Location: ../index.php");
}