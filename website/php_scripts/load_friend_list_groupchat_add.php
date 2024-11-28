<?php
// Loads list of friends when adding them to groupchat
require "avoid_errors.php";

// Get the list of people in groupchat and black list them from the search
$stmt = $conn->prepare("SELECT user_id FROM chats WHERE tablename = ?");
$stmt->bind_param("s", $_SESSION['current_table']);
$stmt->execute();
$result = $stmt->get_result();

$blacklist = array();
while ($row = $result->fetch_assoc()) {
    // Add the users to blacklist
    array_push($blacklist, $row['user_id']);
}
$stmt->close();

$sql = "SELECT user_id_2 FROM friend_list WHERE user_id_1 = " . $_SESSION['user_id'];
$result = $conn->query($sql);
if ((mysqli_num_rows($result) <= 0)) {
    echo "<p id='none-found-p'>Looks like you dont have any friends...</p>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $allFriendsAdded = true;
        if (!in_array($row['user_id_2'], $blacklist)) {
            $allFriendsAdded = false;
            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->bind_param("s", $row['user_id_2']);
            $stmt->execute();

            // Prints out user profiles for every friend
            $result2 = $stmt->get_result();
            $row2 = $result2->fetch_assoc();
            printf("<div class='create-groupchat-friend-container' id='" . $row2['nickname'] . "'>
                        <div class='create-groupchat-friend-profilepic' style='background-image: url(./img/profile_pictures/" . $row2['profile_picture'] . ");'>
                            <img src='./img/borders/" . $row2['profile_border'] . "'>
                        </div>
                        <div class='create-groupchat-friend-name-container'>
                            <h1>" . $row2['nickname'] . "</h1>
                        </div>
                        <input type='checkbox' onclick='highlightCheckbox(%s)' id='checkbox_" . $row2['user_id'] . "' class='create-groupchat-friend-checkbox' name='create-groupchat-checkbox[]' value='" . $row2['user_id'] . "' form='create-groupchat-form'>
                    </div>", '"checkbox_' . $row2['user_id'] . '"');
            $stmt->close();
        }
    }

    if ($allFriendsAdded == true) {
        echo "<p id='none-found-p'>Looks like all your friends are already in this groupchat...</p>";
    }
}