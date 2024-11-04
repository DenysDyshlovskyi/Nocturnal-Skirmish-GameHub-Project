<?php
// Saves rune amount
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['user_id']);
        $new_runes = htmlspecialchars($_POST['new_runes']);

        $stmt = $conn->prepare("UPDATE users SET runes = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $new_runes, $user_id);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}