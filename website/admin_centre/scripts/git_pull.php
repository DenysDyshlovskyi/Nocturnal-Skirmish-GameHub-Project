<?php
// Sends a "message" to powershell to do a git pull
session_start();
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
} else {
	$file = "../../config/cpulog/pull.txt";
    file_put_contents($file, "pull");
}