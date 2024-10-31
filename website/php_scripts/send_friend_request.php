<?php
// Sends friend request to posted user id
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    require "getdate.php";
    $user_id = htmlspecialchars($_POST['user_id']);

    // Checks if the user that youre sending to exists.
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "error";
        exit;
    } else {
        $stmt->close();

        // Checks if user is already in friends list
        $stmt = $conn->prepare("SELECT user_id_2 FROM friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
        $stmt->bind_param("ss", $_SESSION['user_id'], $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ((mysqli_num_rows($result) <= 0)) {
            $isInFriendsList = 0;
        } else {
            $isInFriendsList = 1;
        }
        $stmt->close();

        // Checks if you have already sent friend request to user
        $stmt = $conn->prepare("SELECT user_id_2 FROM pending_friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
        $stmt->bind_param("ss", $_SESSION['user_id'], $user_id);
        $stmt->execute();
        $result2 = $stmt->get_result();
        if ((mysqli_num_rows($result2) <= 0)) {
            $alreadySent = 0;
        } else {
            $alreadySent = 1;
        }
        $stmt->close();

        if ($isInFriendsList == 0 && $alreadySent == 0) {
            // Insert friend request into pending_friend_list table
            $stmt = $conn->prepare("INSERT INTO pending_friend_list (user_id_1, user_id_2, sent) VALUES (?,?,?)");
            $stmt->bind_param("sss", $_SESSION['user_id'], $user_id, $date);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "error";
            exit;
        }
    }
} else {
    header("Location: ../../index.php");
}