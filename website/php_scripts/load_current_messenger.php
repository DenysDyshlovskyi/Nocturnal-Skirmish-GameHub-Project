<?php
// Sets nickname in header to the correct messenger
require "avoid_errors.php";

// If a current messenger has not been set
if (!isset($_SESSION['current_messenger'])) {
    // Get the last chat you sent message in, and set that to the current chat
    $stmt3 = $conn->prepare("SELECT tablename FROM chats WHERE user_id = ? ORDER BY last_chat DESC LIMIT 1");
    $stmt3->bind_param("s", $_SESSION['user_id']);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    if ((mysqli_num_rows($result3) <= 0)) {
        $stmt3->close();
        goto end;
    }
    $row3 = mysqli_fetch_assoc($result3);
    $_SESSION['current_table'] = $row3['tablename'];
    $stmt3->close();

    // Set the current messenger
    $stmt3 = $conn->prepare("SELECT user_id FROM chats WHERE user_id <> ? AND tablename = ?");
    $stmt3->bind_param("ss", $_SESSION['user_id'], $row3['tablename']);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    $row3 = mysqli_fetch_assoc($result3);
    $_SESSION['current_messenger'] = $row3['user_id'];
    $stmt3->close();
}

// Echo the current messenger to screen
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['current_messenger']);
$stmt->execute();
$result = $stmt->get_result();
$row = mysqli_fetch_assoc($result);
echo "<a href='#' onclick='displayUserProfile(" . $row['user_id'] . ")'>
                        <div class='current-messenger-profilepic' style='background-image: url(./img/profile_pictures/" . $row['profile_picture'] . ");'>
                            <img src='./img/borders/" . $row['profile_border'] . "'>
                        </div>
                    </a>
                    <div class='current-messenger-name-container'>
                        <p>" . $row['nickname'] . "</p>
                    </div>";
$stmt->close();

end: