<?php
require "../../php_scripts/avoid_errors.php";
// Resets the current path
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
} else {
    $_SESSION['admin_currentpath'] = "C:\inetpub\wwwroot";
    $_SESSION['admin_previouspath'] = dirname($_SESSION['admin_currentpath']);
}