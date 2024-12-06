<?php
// Loads in a list of online players
require "../../php_scripts/avoid_errors.php";
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
} else {
    // Gets all rows where user is online
    $unix_timestamp = time();
    $stmt = $conn->prepare("SELECT * FROM users WHERE last_login > ?");
    $stmt->bind_param("s", $unix_timestamp);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "No online users...";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='online-list-row'>
            <div class='online-list-profilepic' style='background-image: url(../img/profile_pictures/" . $row['profile_picture'] . ");'>
                <img src='../img/borders/" . $row['profile_border'] . "'>
            </div>
            <h1 class='online-list-username'>" . $row['username'] . "</h1>
            <button class='user-search-button' title='Display profile' form='display-profile-form' name='profile' value='" . $row['user_id'] . "'></button>
        </div>";
        }
    }
}