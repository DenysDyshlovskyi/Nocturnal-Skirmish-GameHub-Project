<?php
    //Gets info of logged in user
    require "avoid_errors.php";

    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    $_SESSION['user_profile_picture'] = "./img/profile_pictures/" . $row['profile_picture'];
    $_SESSION['user_profile_border'] = "./img/borders/" . $row['profile_border'];
    $_SESSION['user_profile_banner'] = "./img/profile_banners/" . $row['profile_banner'];
    $_SESSION['user_profile_description'] = $row['description'];
    $_SESSION['user_profile_username'] = $row['username'];
    $_SESSION['user_profile_nickname'] = $row['nickname'];
    $_SESSION['user_profile_email'] = $row['email'];
?>