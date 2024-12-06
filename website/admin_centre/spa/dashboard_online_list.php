<?php
session_start();
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
};
?>
<style>
    #dark-container {
        display: block;
    }
</style>
<style><?php include "./css/dashboard-online-list.css" ?></style>
<div class="online-list-container">
    <h1 class="online-list-headline">Online List</h1>
    <div class="online-list-result-container">
        <?php include "../scripts/load_online_list.php" ?>
    </div>
    <button onclick="removeDarkContainer()">Close</button>
</div>