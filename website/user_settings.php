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
<body id="settings-body" onload="ajaxGet('./spa/user_settings/myaccount.php', 'settings-spa-container')">
    <div class="settings-container">
        <button class="settings-backtohub" title="Back to Hub" onclick="window.location.href = 'hub.php';">Back to Hub</button>
        <div class="settings-sidebar">
            <p class="settings-sidebar-headline">Profile settings</p>
            <button class="settings-sidebar-button" id="myaccount-button" onclick="ajaxGet('./spa/user_settings/myaccount.php', 'settings-spa-container')">My Account</button>
            <button class="settings-sidebar-button" id="changeborder-button" onclick="ajaxGet('./spa/user_settings/change_border.php', 'settings-spa-container')">Change border</button>
            <p class="settings-sidebar-headline">General settings</p>
            <button class="settings-sidebar-button" id="audiomusic-button" onclick="ajaxGet('./spa/user_settings/audio_music.php', 'settings-spa-container')">Audio and Music</button>
        </div>
        <div class="settings-spa-container" id="settings-spa-container">
        </div>
    </div>
    <audio autoplay loop style="display: none;">
        <source src="audio/OldJazzOST.mp3" type="audio/mpeg">
    </audio>
    <script><?php include "./js/script.js" ?></script>
</body>
</html>