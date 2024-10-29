<?php
//Gets every user in your friends list that is offline.
require "avoid_errors.php";
$offlineFriends = 0;

$sql = "SELECT user_id_2 FROM friend_list WHERE user_id_1 = " . $_SESSION['user_id'];
$result = $conn->query($sql);
if ((mysqli_num_rows($result) <= 0)) {
    echo "No friends";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $row['user_id_2']);
        $stmt->execute();

        // Prints out user profiles for every offline friend
        $result2 = $stmt->get_result();
        $row2 = $result2->fetch_assoc();

        if ($row2['last_login'] < time()) {
            $offlineFriends = 1;
            echo "<div class='hub-friends-list-profile-container' id='" . $row2['user_id'] . "'>
                <a href='#' onclick='displayUserProfile(" . $row2['user_id'] . ")' class='hub-friends-list-profilepic-link'>
                    <div class='hub-friends-list-profilepic' style='background-image: url(" . "./img/profile_pictures/" . $row2['profile_picture'] . ");'>
                        <img src='" . "./img/borders/" . $row2['profile_border'] . "'>
                    </div>
                </a>
                <div class='hub-friends-list-profile-name-container'>
                    <h1>" . $row2['nickname'] . "</h1>
                    <div class='hub-friends-list-profile-name-container-line'></div>
                </div>
                <button class='hub-friends-list-profile-message-button'></button>
                <button class='hub-friends-list-profile-more-button' onclick='openMoreOptionsFriendsList(" . $row2['user_id'] . ")'></button>
            </div>";
        };
        $stmt->close();
    }
    if ($offlineFriends == 0) {
        echo "No offline friends";
    };
}