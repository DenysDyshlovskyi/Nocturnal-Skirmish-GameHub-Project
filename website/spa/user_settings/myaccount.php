<?php
session_start();
?>
<h1 class="settings-headline">My Account</h1>
<div class="settings-myaccount-inner">
    <div class="settings-myaccount-banner" style="background-image: url(<?php echo $_SESSION['user_profile_banner'] ?>);"></div>
    <button class="settings-myaccount-change-banner">Change banner</button>
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
    <textarea class="settings-myaccount-description" maxlength="500"><?php echo $_SESSION['user_profile_description'] ?></textarea>
    <button class="settings-myaccount-save-button">Save</button>
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

        </div>
        <div class="settings-myaccount-change-component">

        </div>
    </div>
</div>
<style>
    #myaccount-button {
        background-color: #FFCF8C;
    }
</style>