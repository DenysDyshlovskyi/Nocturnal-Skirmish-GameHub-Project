<?php
// Loads in a list of chats the user is in.
require "avoid_errors.php";

// Check which chats the user is in
$stmt = $conn->prepare("SELECT * FROM chats WHERE user_id = ? ORDER BY last_chat DESC");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ((mysqli_num_rows($result) <= 0)) {
    echo "
    <div class='no-chats-container'>
        <p>Looks like you're not in any chats...</p>
        <p>Click the + to create a new chat!</p>
    </div>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        // If chat type is 'two_user', show the the other users profile pic, border and username.
        if ($row['type'] == 'two_user') {
            $stmt2 = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND user_id <> ?");
            $stmt2->bind_param("ss", $row['tablename'], $_SESSION['user_id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = mysqli_fetch_assoc($result2);
            $friend = $row2['user_id'];

            // If the chat is the current chat, put border around button
            if ($friend == $_SESSION['current_messenger']) {
                $border = "style='border: 2px solid black;'";
            } else {
                $border = "";
            }


            $stmt2 = $conn->prepare("SELECT * FROM users WHERE user_id = $friend");
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = mysqli_fetch_assoc($result2);

            // If user is online, show green circle, else, show red circle and turn down the opacity
            if ($row2['last_login'] > time()) {
                // Online
                $onlineOfflineCircle = "<img src='./img/icons/online.svg' class='messages-menu-offline-online'>";
                $offlineOpacity = "";
            } else {
                // Offline
                $onlineOfflineCircle = "<img src='./img/icons/offline.svg' class='messages-menu-offline-online'>";
                $offlineOpacity = "opacity: 0.6;";
            }

            printf("<button class='messages-menu-button' onclick='selectChat(%s)' $border>
                        $onlineOfflineCircle
                        <div class='messages-menu-button-profilepic' style='$offlineOpacity background-image: url(./img/profile_pictures/" . $row2['profile_picture'] . ");'>
                            <img src='./img/borders/" . $row2['profile_border'] . "'>
                        </div>
                        <div class='messages-menu-button-name-container' style='$offlineOpacity'>
                            <p>" . $row2['nickname'] . "</p>
                        </div>
                    </button>", '"' . $row['tablename'] . '"');
        }
    }
}