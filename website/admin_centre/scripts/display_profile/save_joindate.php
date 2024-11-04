<?php
// Saves joindate
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['user_id']);
        $new_joindate = htmlspecialchars($_POST['new_joindate']);

        $stmt = $conn->prepare("UPDATE users SET joindate = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $new_joindate, $user_id);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}