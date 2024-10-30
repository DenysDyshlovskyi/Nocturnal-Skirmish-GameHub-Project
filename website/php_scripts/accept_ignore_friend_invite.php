<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Accepts or ignores pending friend invites
    require "avoid_errors.php";

    $sender_user_id = htmlspecialchars($_POST['user_id']);
    $type = htmlspecialchars($_POST['type']);

    //Check if sender is a real user
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $sender_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "error";
        $stmt->close();
        exit;
    }
    $stmt->close();

    if ($type == 1) {
        //Accept

        //Put senders user id into logged in users friends list
        $stmt = $conn->prepare("INSERT INTO friend_list (user_id_1, user_id_2) VALUES (?,?)");
        $stmt->bind_param("ss", $_SESSION['user_id'], $sender_user_id);
        $stmt->execute();
        $stmt->close();

        //Put logged in user id into senders friends list
        $stmt = $conn->prepare("INSERT INTO friend_list (user_id_1, user_id_2) VALUES (?,?)");
        $stmt->bind_param("ss", $sender_user_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();

        echo "accepted";
    } else if ($type == 0) {
        echo "ignored";
    } else {
        echo "error";
        exit;
    }

    //Remove invite from pending
    $stmt = $conn->prepare("DELETE FROM pending_friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
    $stmt->bind_param("ss", $sender_user_id, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
} else {
    header("Location: ../../index.php");
}