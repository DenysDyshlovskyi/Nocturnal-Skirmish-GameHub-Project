<?php
// Handles login form for admins with tight security.
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['admin_password']) || empty($_POST['admin_password'])) {
        header("Location: ../admin_login.php?error=empty");
        exit;
    } else {
        $password = htmlspecialchars(stripslashes($_POST['admin_password']));

        // Gets admin password in hash form
        require "../../config/admin_hash.php";

        if (password_verify($password, $admin_hash)){
            session_start();
            $_SESSION['isadmin'] = 1;
            header("Location: ../dashboard.php");
        } else {
            header("Location: ../admin_login.php?error=wrong");
            exit;
        }
    }
} else {
    header("Location: ../admin_login.php?error=unauth");
    exit;
}