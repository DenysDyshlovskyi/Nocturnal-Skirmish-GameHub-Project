<?php
// Dashboard for admins
session_start();

// If user is unauthorized, redirect them
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: admin_login.php?error=unauth");
}
?>