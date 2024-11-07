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
    <title>GameHub</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/hub-page.css" ?> </style>
</head>
<body id="hub-body" onload="prepareSFX(); ajaxGet('./php_scripts/update_login_time.php', 'players-live-count', 'no_sfx'); isKicked()">
    <div id="dark-container" class="dark-container"></div>
    <div class="confirmation-popup" id="confirmContainer"></div>
    <div class="hub-spa-container" id="hub-spa-container"></div>
    <div class="hub-corner-profile-container">
        <div class="hub-corner-profilepic-container" id="hub-corner-profilepic-container">
            <a href="#" onclick="displayUserProfile(<?php echo $_SESSION['user_id'] ?>)">
                <img src="<?php echo $_SESSION['user_profile_border']; ?>" alt="Profile Border" class="hub-corner-profilepic-border">
                <div class="hub-corner-profilepic" style="background-image: url(<?php echo $_SESSION['user_profile_picture']; ?>);"></div>
            </a>
        </div>
        <div class="hub-corner-profile-dropdown">
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-settings" title="Settings" onclick="window.location.href = 'user_settings.php';"></button>
            <div class="hub-corner-profile-dropdown-divider"></div>
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-friends" title="Friends" onclick="ajaxGet('./spa/hub/friends_list.php', 'hub-spa-container', 'friends_list'); displaySpaContainerHub('block');"></button>
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
    <div class="main-menu-middle-ui-container">
        <div class="main-menu-middle-ui"
            style="display: flex; justify-content: center; flex-direction: column; align-items: center; gap: 20px;">
            <h1 class="title-text">GameHub™</h1>
            <div class="menu-selection-buttons">
                <button class="play-button">Play <img style="width: 30%;" src="img/Noc_Skir_Logo.svg" alt="Logo"></button>
                <br>
                <button style="margin-bottom: 10px; margin-right: 10px;" class="menu-button">Inventory</button>
                <a class="link" href="Featured.html"><button class="menu-button">Shop</button></a>
                <br>
                <button style="margin-bottom: 10px; margin-right: 10px;" class="menu-button">Tutorial</button>
                <button class="menu-button" onclick="ajaxGet('./spa/hub/friends_list.php', 'hub-spa-container', 'friends_list'); displaySpaContainerHub('block');">Friends List</button>
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
    </div>   

    <!-- Footer container with trademark text and live player count -->
    <footer>
        <p class="footer-tm-text">GameHub™ 2024</p>
        <p class="player-live-count-text" id="players-live-count"></p>
    </footer>
    <!-- /HUB2-IMPORT -->

    <!-- Autolooping audio background music (works only if user allows it) -->
    <audio autoplay loop style="display: none;" id="musicAudio">
        <source src="./audio/music/BrowsingShopOST.mp3" type="audio/mpeg">
    </audio>

    <!-- hover audio temp -->
    <audio id='hoverSFX'>
        <source src="audio/sfx/hover.mp3" type="audio/mpeg">
    </audio>
    <!-- click sfx temp -->
    <audio id='clickSFX'>
        <source src="audio/sfx/click1.mp3" type="audio/mpeg">
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

    // Sets default volumes if it is first time youre making a user.
    if(localStorage.getItem("volumeUi") === null) {
        localStorage.setItem("volumeUi", 1);
    }

    if(localStorage.getItem("volumeMusic") === null) {
        localStorage.setItem("volumeMusic", 1);
    }

    // Starts 5 second interval to update players online counter
    setInterval(function(){
        ajaxGet('./php_scripts/update_login_time.php', 'players-live-count', 'no_sfx');
    }, 5000);
</script>
</html>