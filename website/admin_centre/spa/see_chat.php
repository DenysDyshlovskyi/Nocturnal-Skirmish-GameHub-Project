<?php
session_start();
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
};
?>
<!-- Using styling from messages.php -->
<style><?php include "../../css/messages.css" ?></style>
<style><?php include "./css/see-chat.css" ?></style>
<div class="seechat-container">
    <h1 class="seechat-headline">Tablename: <?php echo $_SESSION['current_admin_seechat'] ?></h1>
    <div class="seechat-chat-container">
        <?php include "../scripts/admin_loadmessages.php" ?>
    </div>
    <button class="seechat-close" onclick="removeDarkContainer()">Close</button>
</div>