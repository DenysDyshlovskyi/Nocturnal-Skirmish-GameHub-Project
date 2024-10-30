<?php
//Gets every invite sent to logged in user and displays them
require "avoid_errors.php";

$sql = "SELECT * FROM pending_friend_list WHERE user_id_2 = " . $_SESSION['user_id'];
$result = $conn->query($sql);
if ((mysqli_num_rows($result) <= 0)) {
    echo "No pending invites";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $row['user_id_1']);
        $stmt->execute();

        $result2 = $stmt->get_result();
        $row2 = $result2->fetch_assoc();

        echo "
        <div class='hub-add-friends-pending-profile'>
            <a href='#' onclick='displayUserProfile(" . $row2['user_id'] . ")' class='hub-add-friends-pending-profilepic-link'>
                <div class='hub-add-friends-pending-profilepic' style='background-image: url(./img/profile_pictures/" . $row2['profile_picture'] . ");'>
                    <img src='./img/borders/" . $row2['profile_border'] . "'>
                </div>
            </a>
            <div class='hub-add-friends-pending-profile-text-container'>
                <p>Received " . $row['sent'] . "</p>
                <h1>" . $row2['nickname'] . "</h1>
                <div class='hub-add-friends-pending-profile-text-container-line'></div>
            </div>
            <div class='hub-add-friends-pending-profile-button-container'>
                <button id='accept_pending_button' onclick='acceptIgnoreFriendInvite(" . $row2['user_id'] . ", 1)'>Accept</button>
                <button id='ignore_pending_button' onclick='acceptIgnoreFriendInvite(" . $row2['user_id'] . ", 0)'>Ignore</button>
            </div>
        </div>
        <div class='hub-add-friends-pending-profile-divider'></div>
        ";
    }
}