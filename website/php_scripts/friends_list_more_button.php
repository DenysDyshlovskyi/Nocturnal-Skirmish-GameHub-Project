<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gets necessary information about user for more button in friends list
    require "avoid_errors.php";
    $_SESSION['more_button_userid'] = htmlspecialchars($_POST['user_id']);
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['more_button_userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $_SESSION['more_button_profilepic'] = $row['profile_picture'];
    $_SESSION['more_button_border'] = $row['profile_border'];
    $_SESSION['more_button_nickname'] = $row['nickname'];
} else {
    header("Location: ../../index.php");
}