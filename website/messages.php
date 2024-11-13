<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Messages</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/messages.css" ?> </style>
</head>
<body id="messages-body" onload="prepareSFX(); ajaxGet('./php_scripts/update_login_time.php', 'players-live-count', 'no_sfx'); isKicked(); scrollToBottom()">
<div id="dark-container" class="dark-container"></div>
<div class="confirmation-popup" id="confirmContainer"></div>
    <div class="messages-content-container">
        <header>
            <div class="current-messenger-container">
                <div class="current-messenger-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                    <img src="./img/borders/defaultborder.webp">
                </div>
                <div class="current-messenger-name-container">
                    <p>BimBomSlimSlom</p>
                </div>
            </div>
            <button class="messages-backtohub" title="Back to Hub" onclick="window.location.href = 'hub.php';">Back to Hub</button>
        </header>
        <div class="messages-menu">
            <div class="messages-menu-top">Messages</div>
            <div id="messages-menu-chats-container">
                <button class="messages-menu-button">
                    <div class="messages-menu-button-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                        <img src="./img/borders/defaultborder.webp">
                    </div>
                    <div class="messages-menu-button-name-container">
                        <p>BimBomSlimSlom</p>
                    </div>
                </button>
            </div>
        </div>

        <div class="messages-container" id="messages-container">
            <div class="message-container">
                <div class="message-name-container">
                    <div class="message-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                        <img src="./img/borders/defaultborder.webp">
                    </div>
                    <h1 class="message-nickname">BimBomSlimSlom - <i>20-04-2024 10:30</i></h1>
                </div>
                <div class="message-content">
                    <p>Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha</p>
                </div>
            </div>
            <div class="message-container">
                <div class="message-name-container">
                    <div class="message-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                        <img src="./img/borders/defaultborder.webp">
                    </div>
                    <h1 class="message-nickname">BimBomSlimSlom - <i>20-04-2024 10:30</i></h1>
                </div>
                <div class="message-content">
                    <p>Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha</p>
                </div>
            </div>
            <div class="message-container">
                <div class="message-name-container">
                    <div class="message-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                        <img src="./img/borders/defaultborder.webp">
                    </div>
                    <h1 class="message-nickname">BimBomSlimSlom - <i>20-04-2024 10:30</i></h1>
                </div>
                <div class="message-content">
                    <p>Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha</p>
                </div>
            </div>
            <div class="message-container">
                <div class="message-name-container">
                    <div class="message-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                        <img src="./img/borders/defaultborder.webp">
                    </div>
                    <h1 class="message-nickname">BimBomSlimSlom - <i>20-04-2024 10:30</i></h1>
                </div>
                <div class="message-content">
                    <p>Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha</p>
                </div>
            </div>
            <div class="message-container">
                <div class="message-name-container">
                    <div class="message-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                        <img src="./img/borders/defaultborder.webp">
                    </div>
                    <h1 class="message-nickname">BimBomSlimSlom - <i>20-04-2024 10:30</i></h1>
                </div>
                <div class="message-content">
                    <p>Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha</p>
                </div>
            </div>
            <div class="message-container">
                <div class="message-name-container">
                    <div class="message-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                        <img src="./img/borders/defaultborder.webp">
                    </div>
                    <h1 class="message-nickname">BimBomSlimSlom - <i>20-04-2024 10:30</i></h1>
                </div>
                <div class="message-content">
                    <p>Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha</p>
                </div>
            </div>
            <div class="message-container">
                <div class="message-name-container">
                    <div class="message-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                        <img src="./img/borders/defaultborder.webp">
                    </div>
                    <h1 class="message-nickname">BimBomSlimSlom - <i>20-04-2024 10:30</i></h1>
                </div>
                <div class="message-content">
                    <p>Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha Jeg er en skummel monster muhahaha</p>
                </div>
            </div>
        </div>

        <div class="message-bar">
            <input type="text" class="message-bar-text-input">
            <div class="message-bar-more-container">
                <button title="Send message" id="send-button"></button>
                <button title="Add attachment" id="attachment-button"></button>
                <button title="Add emoji" id="emoji-button"></button>
            </div>
        </div>
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
    <script>
        // Checks if user should be kicked
        function isKicked() {
            var placeholder = "placeholder";
            $.ajax({
                type: "POST",
                url: './php_scripts/kick.php',
                data:{ placeholder : placeholder }, 
                success: function(response){
                    if (response == "kick") {
                        window.location.href = "index.php";
                    }
                }
            })
        }

        // Starts 5 second interval to update players online counter, and to check if user should be kicked
        setInterval(function(){
            ajaxGet('./php_scripts/update_login_time.php', 'players-live-count', 'no_sfx');
            isKicked()
        }, 5000);

        // Scrolls to bottom of messages
        function scrollToBottom() {
            container = document.getElementById("messages-container");
            container.scrollTop = container.scrollHeight;
        }
    </script>
</body>
</html>