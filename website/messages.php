<?php
session_start();
$_SESSION['message_amount'] = 15;
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
            <button class="messages-backtohub" title="Back to Hub" onclick="window.location.href = 'hub.php';">Back to Hub</button>
        </header>
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

        <div class="message-bar">
            <textarea class="message-bar-text-input" maxlength="500" id="message-input" oninput='resizeTextArea()'></textarea>
            <div class="message-bar-more-container">
                <button title="Send message" id="send-button" onclick="sendMessage()"></button>
                <button title="Add attachment" id="attachment-button"></button>
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
        // Resizes message input based on how much text is inside of it
        function resizeTextArea() {
            var textarea = document.getElementById("message-input");
            var messagesContainer = document.getElementById("messages-container");

            textarea.style.height = "";
            textarea.style.height = textarea.scrollHeight + "px";
            height = "calc(100vh - " + (textarea.scrollHeight + 227) + "px)";
            if ((textarea.scrollHeight + 227) < 338) {
                messagesContainer.style.setProperty('height', height);
                scrollToBottom();
            }
        }

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

        var atBottom = 0;
        // Checks if the user is at the bottom of page
        function isAtBottom() {
            const scrollableDiv = document.getElementById('messages-container');
            const { scrollTop, scrollHeight, clientHeight } = scrollableDiv;

            // Check if the user has scrolled to the bottom
            if (scrollTop + clientHeight >= scrollHeight) {
                atBottom = 1;
            } else {
                atBottom = 0;
            }
        }

        // Checks if the user was at the bottom of page before new messages were loaded in
        function stillAtBottom() {
            if (atBottom == 1) {
                scrollToBottom();
            }
        }

        // Loads in messages, and scrolls to bottom of page to view new messages
        function loadMessages() {
            ajaxGet("./php_scripts/load_messages.php", "messages-container", "still_at_bottom");
        }

        // Starts 3 second interval to update messages
        setInterval(function(){
            isAtBottom();
            setTimeout(loadMessages, 500);
        }, 3000);

        // When user scrolls to top of messages, load in 25 more messages
        const scrollableDiv = document.getElementById('messages-container');
        var first_message_id;

        // Jumps to previous last message, so that the same message before loading in the new ones is shown.
        function scrollToDiv() {
            const container = document.getElementById('messages-container');
            const target = document.getElementById(first_message_id);
            const offsetTop = target.offsetTop - container.offsetTop;
            container.scrollTo({ top: offsetTop});
        }

        // Jumps to previous last message when new messages are loaded in
        function jumpToLastMessage() {
            var messages_container = document.getElementsByClassName('messages-container');
            var first_message = messages_container[0].children[0];
            first_message_id = first_message.id;
            $.get('./php_scripts/load_more_messages.php');
            ajaxGet("./php_scripts/load_messages.php", "messages-container", "scrollToDiv");
        }

        // When user has scrolled to top of messages, load in 15 new ones
        scrollableDiv.addEventListener('scroll', () => {
            // Check if the user has scrolled to the top
            if (scrollableDiv.scrollTop === 0) {
                jumpToLastMessage();
            }
        });
    </script>
</body>
</html>