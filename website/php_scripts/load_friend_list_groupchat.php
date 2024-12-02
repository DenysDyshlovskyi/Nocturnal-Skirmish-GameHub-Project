<?php
// Loads list of friends when creating groupchat
require "avoid_errors.php";

$sql = "SELECT user_id_2 FROM friend_list WHERE user_id_1 = " . $_SESSION['user_id'];
$result = $conn->query($sql);
if ((mysqli_num_rows($result) <= 0)) {
    echo "<p id='none-found-p'>Looks like you dont have any friends...</p>";
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $row['user_id_2']);
        $stmt->execute();

        // Prints out user profiles for every friend
        $result2 = $stmt->get_result();
        $row2 = $result2->fetch_assoc();
        printf("<div class='create-groupchat-friend-container' id='" . $row2['nickname'] . "_" . $row2['username'] . "'>
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