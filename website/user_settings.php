<?php
session_start();

require "./php_scripts/conn.php";
require "./php_scripts/get_loggedin_info.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - User settings</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/user-settings-page.css" ?> </style>
</head>
<body id="settings-body">
    <div class="settings-container">
        <button class="settings-backtohub" title="Back to Hub" onclick="window.location.href = 'hub.php';">Back to Hub</button>
        <div class="settings-sidebar">
            <p class="settings-sidebar-headline">Profile settings</p>
            <button class="settings-sidebar-button">My Account</button>
            <button class="settings-sidebar-button">Change border</button>
            <p class="settings-sidebar-headline">General settings</p>
            <button class="settings-sidebar-button">Audio and Music</button>
        </div>
        <div class="settings-myaccount-container">
            <h1 class="settings-headline">My Account</h1>
            <div class="settings-myaccount-inner">
                <div class="settings-myaccount-banner" style="background-image: url(<?php echo $_SESSION['user_profile_banner'] ?>);"></div>
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
            </div>
        </div>
    </div>
    <audio autoplay loop style="display: none;">
        <source src="audio/OldJazzOST.mp3" type="audio/mpeg">
    </audio>
</body>
</html>