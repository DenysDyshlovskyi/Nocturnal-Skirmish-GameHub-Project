<?php
require "../../php_scripts/avoid_errors.php";
// Sets the current path for a file explorer
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
} else {
    $folder = htmlspecialchars($_POST['folder']);
    if (is_dir($_SESSION['admin_currentpath'] . "/" . $folder)) {
        $_SESSION['admin_currentpath'] = $_SESSION['admin_currentpath'] . "/" . $folder;
        $_SESSION['admin_previouspath'] = dirname($_SESSION['admin_currentpath']);
    } else {
        echo "error";
    }
}