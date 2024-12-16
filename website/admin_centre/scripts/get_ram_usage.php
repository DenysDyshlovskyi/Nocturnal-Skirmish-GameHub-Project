<?php
// Gets RAM usage percentage of server by opening log file managed by powershell script
session_start();
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
} else {
	$gbAmount = file_get_contents('../../config/cpulog/ram_log.txt');
    echo $gbAmount;
}