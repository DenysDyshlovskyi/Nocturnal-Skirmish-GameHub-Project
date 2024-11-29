<?php
// Loads in rows of members in a groupchat
require "avoid_errors.php";
$stmt = $conn->prepare("SELECT user_id FROM chats WHERE tablename = ? AND type = 'groupchat'");
$stmt->bind_param("s", $_SESSION['current_table']);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows > 0){
    while ($row = mysqli_fetch_assoc($result)) {
        $stmt2 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt2->bind_param("s", $row['user_id']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $row2 = mysqli_fetch_assoc($result2);

        // If user is offline, grey out their name and put a red circle next to them
        if ($row2['last_login'] < time()) {
            $opacityStyling = "opacity: 0.5;";
            $onlineOfflineTitle = "title='This user is offline.'";
            $offlineOnlineCircle = "<img src='./img/icons/offline.svg' class='offline-online-circle-groupchat'>";
        } else {
            // If the user is online, put a green circle next to them
            $opacityStyling = "";
            $onlineOfflineTitle = "title='This user is online.'";
            $offlineOnlineCircle = "<img src='./img/icons/online.svg' class='offline-online-circle-groupchat'>";
        }
        echo "
            <div class='member-list-row' $onlineOfflineTitle>
                $offlineOnlineCircle
                <div class='member-list-row-profilepic' style='background-image: url(./img/profile_pictures/" . $row2['profile_picture'] . "); $opacityStyling'>
                    <img src='./img/borders/" . $row2['profile_border'] . "'>
                </div>
                <p class='member-list-nickname' style='$opacityStyling'>" . $row2['nickname'] . "</p>
            </div>";
    }
}

$stmt->close();