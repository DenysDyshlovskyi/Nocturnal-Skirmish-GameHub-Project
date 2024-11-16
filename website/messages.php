<?php
session_start();
$_SESSION['message_amount'] = 15;

if (isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == 1) {
    echo "Viewing as admin";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Messages</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="./lib/LC-emoji-picker-master/lc_emoji_picker.min.js"></script>
    <!--Using LC Emoji Picker: https://lcweb.it/lc-emoji-picker-javascript-plugin/-->
    <script type="text/javascript"> new lc_emoji_picker('textarea, input'); </script>
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/messages.css" ?> </style>
</head>
<body id="messages-body" onload="prepareSFX(); ajaxGet('./php_scripts/update_login_time.php', 'players-live-count', 'no_sfx'); isKicked(); scrollToBottom();">
<div id="dark-container" class="dark-container"></div>
<div class="confirmation-popup" id="confirmContainer"></div>
    <div class="messages-content-container">
        <header>
            <div class="current-messenger-container" id="current-messenger-container">
                <?php include "./php_scripts/load_current_messenger.php" ?>
            </div>
            <?php
            if (isset($_SESSION['isadmin']) && $_SESSION['isadmin'] == 1) {
                printf("<button class='messages-backtohub' style='width: 200px' title='Back to Dashboard' onclick='window.location.href = %s;'>Back to Dashboard</button>", '"' . "./admin_centre/dashboard.php" . '"');
            } else {
                printf("<button class='messages-backtohub' title='Back to Hub' onclick='window.location.href = %s;'>Back to Hub</button>", '"' . "hub.php" . '"');
            }
            ?>
        </header>
        <div class="loading-container" id="loading"><img src="./img/icons/loading.gif"></div>
        <div class="messages-menu">
            <div class="messages-menu-top">Messages</div>
            <div id="messages-menu-chats-container">
                <?php include "./php_scripts/load_chat_list.php" ?>
            </div>
            <button class="messages-add-chat-button" title="Create new chat" onclick="localStorage.setItem('openFriendList', 1); window.location.href = 'hub.php';"></button>
        </div>

        <div class="messages-container" id="messages-container">
            <?php include "./php_scripts/load_messages.php"; ?>
        </div>
        <div class="more-button-popup" id="more-button-popup">
            <div class="more-button-popup-button" id="attach-file">
                <input type="file" class="file-upload" name="file-upload" form="message-send-form"/>
                <img src="./img/icons/paper-clip.svg" alt="Attach file">Attach file
            </div>
            <div class="more-button-popup-button">
                <input type="file" id="media-file-input" class="file-upload" onchange="preview()" name="media-upload" form="message-send-form" accept="image/png, image/gif, image/jpeg, image/webp"/>
                <img src="./img/icons/image.svg" alt="Attach media">Attach media
            </div>
        </div>
        <div class="message-bar" id="message-bar">
            <div class="message-bar-input-img-container">
                <div class="media-preview-container" id="media-preview-container">
                    <button title="Remove attachment" onclick="removeMedia()"></button>
                    <img src="./img/profile_banners/defaultbanner.jpg" class="message-bar-preview-image" id="media-preview">
                </div>
                <textarea class="message-bar-text-input" name="message-text" form="message-send-form" maxlength="500" id="message-input" oninput='resizeTextArea(); resizeMessageBar();' onkeydown = "if (event.keyCode == 13){ $('#message-send-form').submit() }" spellcheck="false"></textarea>
            </div>
            <div class="message-bar-more-container">
                <button title="Send message" id="send-button" type="submit" form="message-send-form"></button>
                <button title="Add attachment" id="attachment-button" onclick="hideShowAttachMenu()"></button>
            </div>
        </div>
        <form id="message-send-form" method="POST" enctype="multipart/form-data"></form>
    </div>

    <footer>
        <p class="footer-tm-text">GameHub™ 2024</p>
        <p class="player-live-count-text" id="players-live-count"></p>
    </footer>
    <audio autoplay loop style="display: none;" id="musicAudio">
        <source src="audio/music/MessagesOST.mp3" type="audio/mpeg">
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
    <script><?php include "./js/messages.js" ?></script>
</body>
</html>