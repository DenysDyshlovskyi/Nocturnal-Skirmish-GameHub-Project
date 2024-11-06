<?php
// ui for banning user
session_start();
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
};
?>
<!-- Reusing styling for profilepic crop -->
<style><?php include "./css/ban-user.css" ?></style>
<style>
    #dark-container {
        display: block !important;
    }
</style>
<div class="ban-container">
    <h1>Ban user: uID <?php echo $_SESSION['displayprofile_userid'] ?></h1>
    <b>Type:</b>
    <form action="display_profile.php" method="post">
        <input type="checkbox" onclick="$('#perm').prop('checked', false); $('#temp').prop('checked', true); banType('temp');" id="temp"> Temporary
        <input type="checkbox" onclick="$('#temp').prop('checked', false); $('#perm').prop('checked', true); banType('perm')" id="perm"> Permanent
        <div id="hide-show-ban">
            <p id="ban-duration"><b>Banned till:</b><br> <input type="date" id="ban-duration-input"></p>
            <b>Reason for ban:</b><br>
            <textarea name="" id=""></textarea>
            <br>
            <input type="submit" value="Ban user" class="ban-button">
            <br>
        </div>
    </form>
    <button onclick="removeDarkContainer()" class="ban-cancel">Cancel</button>
</div>