<?php
    //Gets profile image and border of logged in user
    require "avoid_errors.php";

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $_SESSION['user_profile_picture'] = "./img/profile_pictures/" . $row['profile_picture'];
    $_SESSION['user_profile_border'] = "./img/borders/" . $row['profile_border'];
?>