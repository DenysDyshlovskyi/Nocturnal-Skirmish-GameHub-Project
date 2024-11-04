<?php
// Removes all pending friends when remove all button is pressed
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['user_id']);

        // Removes outgoing pending friends
        $stmt = $conn->prepare("DELETE FROM pending_friend_list WHERE user_id_1 = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->close();

        // Removes incoming pending friends
        $stmt = $conn->prepare("DELETE FROM pending_friend_list WHERE user_id_2 = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}