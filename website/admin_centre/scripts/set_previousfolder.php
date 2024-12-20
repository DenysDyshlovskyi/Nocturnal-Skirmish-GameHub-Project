<?php
require "../../php_scripts/avoid_errors.php";
// Sets the current path as the prevous folder in file explorer
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
} else {
    $_SESSION['admin_currentpath'] = $_SESSION['admin_previouspath'];
    $_SESSION['admin_previouspath'] = dirname($_SESSION['admin_currentpath']);
}