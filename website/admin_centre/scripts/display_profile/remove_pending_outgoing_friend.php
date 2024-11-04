<?php
// Removes pending outgoing friend specified when remove button is pressed
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id_1 = htmlspecialchars($_POST['user_id_1']);
        $user_id_2 = htmlspecialchars($_POST['user_id_2']);

        // Removes outgoing pending friends
        $stmt = $conn->prepare("DELETE FROM pending_friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
        $stmt->bind_param("ss", $user_id_1, $user_id_2);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}