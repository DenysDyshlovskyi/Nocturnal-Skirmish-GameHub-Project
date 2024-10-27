<?php
require "avoid_errors.php";

// Sets correct session variables to display
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $posted_userid = htmlspecialchars($_POST['user_id']);
    if ($posted_userid == "loggedin") {
        // Display the user profile of the logged in user
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        // Displays the user profile of the user with user id that matched the user id that was posted
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $posted_userid);
        $stmt->execute();
        $result = $stmt->get_result();
    };

    if ($result) {
        $row = $result->fetch_assoc();
    } else {
        echo "error";
        exit;
    };

    //Sets the session variables to later be displayed
    $_SESSION['userprofile_display_profilepic'] = "./img/profile_pictures/" . $row['profile_picture'];
    $_SESSION['userprofile_display_border'] = "./img/borders/" . $row['profile_border'];
    $_SESSION['userprofile_display_banner'] = "./img/profile_banners/" . $row['profile_banner'];
    $_SESSION['userprofile_display_description'] = $row['description'];
    $_SESSION['userprofile_display_username'] = $row['username'];
    $_SESSION['userprofile_display_nickname'] = $row['nickname'];
    $_SESSION['userprofile_display_runes'] = $row['runes'];
    $_SESSION['userprofile_display_joindate'] = $row['joindate'];
} else {
    header("Location: ../index.php");
}