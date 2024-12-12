<?php
// Sets the id of which error report to load in
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../admin_login.php?error=unauth");
        exit;
    } else {
        $error_id = htmlspecialchars($_POST['error_id']);
        $_SESSION['current_admin_error_report'] = $error_id;
    }
} else {
    header("Location: ../admin_login.php?error=unauth");
    exit;
}