<?php
// Changes user id to log in as specified user
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['user_id']);
        $_SESSION['user_id'] = $user_id;
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}