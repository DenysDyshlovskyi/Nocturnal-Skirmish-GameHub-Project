<?php
session_start();
?>
<h1 class="settings-headline">My Account</h1>
<div class="settings-myaccount-inner">
    <div class="settings-myaccount-banner" style="background-image: url(<?php echo $_SESSION['user_profile_banner'] ?>);"></div>
    <button class="settings-myaccount-change-banner" onclick="ajaxGet('./spa/user_settings/upload_banner.php', 'settings-dark-container')">Change banner</button>
    <div class="settings-myaccount-profile-container">
        <div class="settings-myaccount-profile-pic-background">
            <div class="settings-myaccount-profile-pic-parent" style="background-image: url(<?php echo $_SESSION['user_profile_picture'] ?>);">
                <img src="<?php echo $_SESSION['user_profile_border'] ?>" alt="Profile Border" class="settings-myaccount-border" draggable="false">
            </div>
        </div>
        <div class="settings-myaccount-name-container">
            <h1><?php echo $_SESSION['user_profile_nickname'] ?></h1>
            <p><?php echo $_SESSION['user_profile_username'] ?></p>
        </div>
    </div>
    <div class="settings-myaccount-profile-pushdown"></div>
    <p class="settings-myaccount-profile-headline">Description</p>
    <textarea class="settings-myaccount-description" id="descriptionTextArea" maxlength="500"><?php echo $_SESSION['user_profile_description'] ?></textarea>
    <button class="settings-myaccount-save-button" id="descriptionSave" onclick="ajaxPost('#descriptionTextArea', './php_scripts/save_description.php', 'Description saved!')">Save</button>
    <p class="settings-myaccount-profile-headline">Account Details</p>
    <div class="settings-myaccount-details-container">
        <div class="settings-myaccount-details-component">
            <p>Nickname</p>
            <h1><?php echo $_SESSION['user_profile_nickname'] ?></h1>
            <button>Edit</button>
        </div>
        <div class="settings-myaccount-details-component">
            <p>Email</p>
            <h1><?php echo $_SESSION['user_profile_email'] ?></h1>
            <button>Edit</button>
        </div>
    </div>
    <div class="settings-myaccount-change-container">
        <div class="settings-myaccount-change-component">
            <p class="settings-myaccount-profile-headline">Profile picture</p>
            <button class="settings-myaccount-change-button">Change profile picture</button>
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