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
    <title>GameHub</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/hub-page.css" ?> </style>
</head>
<body id="hub-body">
    <div class="hub-corner-profile-container">
        <div class="hub-corner-profilepic-container" id="hub-corner-profilepic-container">
            <a href="user_settings.php">
                <img src="<?php echo $_SESSION['user_profile_border']; ?>" alt="Profile Border" class="hub-corner-profilepic-border">
                <div class="hub-corner-profilepic" style="background-image: url(<?php echo $_SESSION['user_profile_picture']; ?>);"></div>
            </a>
        </div>
        <div class="hub-corner-profile-dropdown">
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-settings" title="Settings" onclick="window.location.href = 'user_settings.php';"></button>
            <div class="hub-corner-profile-dropdown-divider"></div>
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-friends" title="Friends"></button>
            <div class="hub-corner-profile-dropdown-divider"></div>
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-chats" title="Chats"></button>
            <div class="hub-corner-profile-dropdown-divider"></div>
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-leaderboard" title="Leaderboard"></button>
            <div class="hub-corner-profile-dropdown-divider"></div>
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-q_and_a" title="QandA"></button>
        </div>
    </div>


<!-- HUB2-IMPORT -->
    <!-- Middle Menu -->
    <div
        style="display: flex; justify-content: center; flex-direction: row; align-items: center; gap: 20px; margin-top: 10%;">
        <div class="main-menu-middle-ui"
            style="display: flex; justify-content: center; flex-direction: column; align-items: center; gap: 20px;">
            <h1 class="title-text">GameHub™</h1>
            <button class="play-button">Play <img style="width: 30%;" src="img/Noc_Skir_Logo.svg" alt="Logo"></button>
            <div class="menu-selection-buttons">
                <button style="margin-bottom: 10px; margin-right: 10px;" class="menu-button">Inventory</button>
                <a class="link" href="Featured.html"><button class="menu-button">Shop</button></a>
                <br>
                <button style="margin-bottom: 10px; margin-right: 10px;" class="menu-button">Tutorial</button>
                <button class="menu-button">Friends List</button>
            </div>
        </div>

        <!-- Patch Notes UI container -->
        <div class="patch-notes-box">
            <h2 class="patch-notes-title">Patch Notes</h2>
            <div class="patch-notes-content-grey">
                <div class="patch-note-list">
                    <div class="patch-note">
                        <h3 class="patch-note-title">Version 1.0.0</h3>
                        <ul class="patch-note-list-items">
                            <li>Added new stuff</li>
                            <li>Updated shop items</li>
                            <li>Added leaderboard feature</li>
                        </ul>
                    </div>
                    <div class="patch-note">
                        <h3 class="patch-note-title">Version 0.9.8</h3>
                        <ul class="patch-note-list-items">
                            <li>Added new stuff</li>
                            <li>Updated shop items</li>
                            <li>Added new stuff</li>
                        </ul>
                    </div>
                    <div class="patch-note">
                        <h3 class="patch-note-title">Version 0.9.7</h3>
                        <ul class="patch-note-list-items">
                            <li>Added new stuff</li>
                            <li>Updated shop items</li>
                            <li>Added new stuff</li>
                        </ul>
                    </div>
                    <div class="patch-note">
                        <h3 class="patch-note-title">Version 0.9.6</h3>
                        <ul class="patch-note-list-items">
                            <li>Added new stuff</li>
                            <li>Updated shop items</li>
                            <li>Added new stuff</li>
                            <li>Added more set_file_buffer</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>     

        <!-- Footer container with trademark text and live player count -->
        <footer>
            <p class="footer-tm-text">GameHub™ 2024</p>
            <p class="player-live-count-text">5 players online</p>
            <img class="live-count-icon" src="img/icons/live-count.svg" alt="live count icon">
        </footer>
    <!-- /HUB2-IMPORT -->

    <!-- Autolooping audio background music (works only if user allows it) -->
    <audio autoplay loop style="display: none;">
        <source src="audio/BrowsingShopOST.mp3" type="audio/mpeg">
    </audio>
</body>
<script><?php include "./js/script.js" ?></script>
<script>
    //Tells the user to enable autoplay once
    audioprompt = localStorage.getItem("audio-prompt");
    if (audioprompt != 1) {
        alert("To enable music and sound effects the website needs autoplay permission.")
        localStorage.setItem("audio-prompt", 1)
    }
</script>
</html>