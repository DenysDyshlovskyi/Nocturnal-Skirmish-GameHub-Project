<?php
session_start();

require "./config/conn.php";
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="./lib/cropper_js/node_modules/cropperjs/dist/cropper.css" rel="stylesheet">
    <script src="./lib/cropper_js/node_modules/cropperjs/dist/cropper.js"></script>
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/user-settings-page.css" ?> </style>
    <!-- Preloads images that might be large -->
    <link rel="preload" as="image" href="<?php echo $_SESSION['user_profile_picture'] ?>" />
    <link rel="preload" as="image" href="<?php echo $_SESSION['user_profile_banner'] ?>" />
</head>
<body id="settings-body" onload="ajaxGet('./spa/user_settings/myaccount.php', 'settings-spa-container'); prepareSFX()">
    <div id="settings-dark-container" class="settings-dark-container">
    </div>
    <div class="settings-confirmation-popup" id="confirmContainer"></div>
    <div class="settings-container">
        <button class="settings-backtohub" title="Back to Hub" onclick="window.location.href = 'hub.php';">Back to Hub</button>
        <div class="settings-sidebar">
            <p class="settings-sidebar-headline">Profile settings</p>
            <button class="settings-sidebar-button" id="myaccount-button" onclick="ajaxGet('./spa/user_settings/myaccount.php', 'settings-spa-container')">My Account</button>
            <button class="settings-sidebar-button" id="changeborder-button" onclick="ajaxGet('./spa/user_settings/change_border.php', 'settings-spa-container')">Change border</button>
            <p class="settings-sidebar-headline">General settings</p>
            <button class="settings-sidebar-button" id="audiomusic-button" onclick="ajaxGet('./spa/user_settings/audio_music.php', 'settings-spa-container', 'audio_music_settings')">Audio and Music</button>
            <p class="settings-sidebar-headline">Miscellaneous</p>
            <button class="settings-sidebar-button" id="devcode-button" onclick="ajaxGet('./spa/user_settings/dev_codes.php', 'settings-spa-container')">Dev Codes</button>
            <button class="settings-sidebar-button-logout" onclick="window.location.href = './php_scripts/logout.php';">Log out</button>
        </div>
        <div class="settings-spa-container" id="settings-spa-container">
        </div>
    </div>
    <!-- div to target with jQuery when nothing should be displayed -->
    <div id="reload-div"></div>
    <audio autoplay loop style="display: none;" id="musicAudio">
        <source src="audio/music/OldJazzOST.mp3" type="audio/mpeg">
    </audio>
    <!-- hover sfx -->
    <audio id='hoverSFX'>
        <source src="./audio/sfx/hover.mp3" type="audio/mpeg">
    </audio>
    <!-- click sfx temp -->
    <audio id='clickSFX'>
        <source src="./audio/sfx/click1.mp3" type="audio/mpeg">
    </audio>
    <script><?php include "./js/script.js" ?></script>
</body>
</html>