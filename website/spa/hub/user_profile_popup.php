<?php
session_start();
?>
<style><?php include "./css/user-profile-popup.css" ?></style>
<style>
    #dark-container {
        display: block !important;
    }
</style>
<div class="hub-userprofile-container">
    <div class="hub-userprofile-banner" style="background-image: url(<?php echo $_SESSION['userprofile_display_banner'] ?>);"></div>
    <div class="hub-userprofile">
        <div class="hub-userprofile-profilepic-background">
            <div class="hub-userprofile-profilepic-parent" style="background-image: url(<?php echo $_SESSION['userprofile_display_profilepic'] ?>);">
                <img class="hub-userprofile-border" src="<?php echo $_SESSION['userprofile_display_border'] ?>">
            </div>
        </div>
        <div class="hub-userprofile-name-container">
            <h1><?php echo $_SESSION['userprofile_display_nickname'] ?></h1>
            <p><?php echo $_SESSION['userprofile_display_username'] . " - " . $_SESSION['userprofile_display_runes'] . " Runes" ?></p>
        </div>
    </div>
</div>