<?php
// Sets the tablename of which chat to load in
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../admin_login.php?error=unauth");
        exit;
    } else {
        $tablename = htmlspecialchars($_POST['tablename']);
        $_SESSION['current_admin_seechat'] = $tablename;
    }
} else {
    header("Location: ../admin_login.php?error=unauth");
    exit;
}