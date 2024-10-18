<?php
session_start();
require "../../php_scripts/get_loggedin_info.php";
?>
<style><?php include "./css/myaccount.css" ?></style>
<h1 class="settings-headline">My Account</h1>
<div class="settings-myaccount-inner">
    <div class="settings-myaccount-banner" id="settings-myaccount-banner" style="background-image: url(<?php echo $_SESSION['user_profile_banner'] ?>);"></div>
    <button class="settings-myaccount-change-banner" onclick="ajaxGet('./spa/user_settings/upload_banner.php', 'settings-dark-container')">Change banner</button>
    <div class="settings-myaccount-profile-container">
        <div class="settings-myaccount-profile-pic-background">
            <div class="settings-myaccount-profile-pic-parent" id="settings-myaccount-profile-pic-parent" style="background-image: url(<?php echo $_SESSION['user_profile_picture'] ?>);">
                <img src="<?php echo $_SESSION['user_profile_border'] ?>" alt="Profile Border" class="settings-myaccount-border" draggable="false">
            </div>
        </div>
        <div class="settings-myaccount-name-container">
            <h1 id="settings-myaccount-nickname"><?php echo $_SESSION['user_profile_nickname'] ?></h1>
            <p><?php echo $_SESSION['user_profile_username'] . " - " . $_SESSION['user_profile_runes'] . " Runes" ?></p>
        </div>
    </div>
    <div class="settings-myaccount-profile-pushdown"></div>
    <p class="settings-myaccount-profile-headline">Description</p>
    <textarea class="settings-myaccount-description" id="descriptionTextArea" maxlength="500"><?php echo $_SESSION['user_profile_description'] ?></textarea>
    <button class="settings-myaccount-save-button" id="descriptionSave" onclick="saveDescription()">Save</button>
    <p class="settings-myaccount-profile-headline">Account Details</p>
    <div class="settings-myaccount-details-container">
        <div class="settings-myaccount-details-component">
            <p>Nickname</p>
            <h1 id="settings-myaccount-details-nickname"><?php echo $_SESSION['user_profile_nickname'] ?></h1>
            <button onclick="ajaxGet('./spa/user_settings/change_nickname.php', 'settings-dark-container')">Edit</button>
        </div>
        <div class="settings-myaccount-details-component">
            <p>Email</p>
            <h1 id="settings-myaccount-details-email"><?php echo $_SESSION['user_profile_email'] ?></h1>
            <button onclick="ajaxGet('./spa/user_settings/change_email.php', 'settings-dark-container')">Edit</button>
        </div>
    </div>
    <div class="settings-myaccount-change-container">
        <div class="settings-myaccount-change-component">
            <p class="settings-myaccount-profile-headline">Profile picture</p>
            <button class="settings-myaccount-change-button" onclick="ajaxGet('./spa/user_settings/upload_profile_picture.php', 'settings-dark-container')">Change profile picture</button>
        </div>
        <div class="settings-myaccount-change-component">
            <p class="settings-myaccount-profile-headline">Password</p>
            <button class="settings-myaccount-change-button">Change password</button>
        </div>
    </div>
</div>
<style>
    #myaccount-button {
        background-color: #FFCF8C;
    }
</style>