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
        echo "
            <div class='member-list-row'>
                <div class='member-list-row-profilepic' style='background-image: url(./img/profile_pictures/" . $row2['profile_picture'] . ");'>
                    <img src='./img/borders/" . $row2['profile_border'] . "'>
                </div>
                <p class='member-list-nickname'>" . $row2['nickname'] . "</p>
            </div>";
    }
}

$stmt->close();