// Contains some JavaScript for messages.php, script tag was getting too cluttered.

        // Hides or shows menu for attaching media or file
        function hideShowAttachMenu() {
            var menu = document.getElementById("more-button-popup");
            if (window.getComputedStyle(menu).display === 'none'){
                $("#more-button-popup").fadeIn(100);
                menu.style.display = 'block';
            } else {
                $("#more-button-popup").fadeOut(100);
            }
        }

        // Resizes message input based on how much text is inside of it
        function resizeTextArea() {
            var textarea = document.getElementById("message-input");

            textarea.style.height = "";
            textarea.style.height = textarea.scrollHeight + "px";
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
            $('#loading').hide();
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
            // Check if the user has scrolled to the top, if they have start loading animation
            if (scrollableDiv.scrollTop === 0) {
                $('#loading').css('display', 'flex');
                setTimeout(jumpToLastMessage, 300);
            }
        });

        // Previews image when uploading image
        function preview() {
            document.getElementById('media-preview').src=URL.createObjectURL(event.target.files[0]);
            document.getElementById('media-preview').style.display = "block";
            document.getElementById('media-preview-container').style.display = "flex";
            document.getElementById("more-button-popup").style.display = "none";
            setTimeout(resizeMessageBar, 100)
        }

        // Resizes messages-container based on height of message bar
        function resizeMessageBar() {
            var messagesContainer = document.getElementById("messages-container");
            document.getElementById("message-bar").style.height = "";
            var messageBar = $("#message-bar").height();

            height = "calc(100vh - " + (messageBar + 215) + "px)";

            messagesContainer.style.setProperty('height', height);
            scrollToBottom();
        }

        // Removes the image attached to message
        function removeMedia() {
            document.getElementById('media-preview').src = "";
            document.getElementById('media-file-input').value = "";
            document.getElementById('media-preview').style.display = "none";
            document.getElementById('media-preview-container').style.display = "none";
            resizeMessageBar()
        }

// Selects a chat to display messages
function selectChat(tablename) {
    $.ajax({
        type: "POST",
        url: './php_scripts/select_chat.php',
        data:{ tablename : tablename }, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.");
            } else {
                ajaxGet("./php_scripts/load_messages.php", "messages-container", "scroll");
                ajaxGet("./php_scripts/load_current_messenger.php", "current-messenger-container");
            }
        }
    })
}

// Scrolls to bottom of messages
function scrollToBottom() {
    container = document.getElementById("messages-container");
    container.scrollTop = container.scrollHeight;
}

// When form for sending message is submitted
$("form#message-send-form").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);

    // Disable inputs for 1 seconds to prevent spam
    $('#message-input').prop('disabled', true);
    $('#send-button').prop('disabled', true);

    $.ajax({
        url: './php_scripts/send_message.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            if (response == "empty") {
                showConfirm("Message is empty!");
                document.getElementById("message-input").value = '';
            } else if (response == "notselected") {
                showConfirm("No chat selected!");
                document.getElementById("message-input").value = '';
            } else {
                ajaxGet("./php_scripts/load_messages.php", "messages-container", "scroll");
                document.getElementById("message-input").value = '';
                removeMedia();
            }
        },
        cache: false,
        contentType: false,
        processData: false,
        complete: function () {
            setTimeout(function () {
                // After 1 seconds, enable inputs again
                $('#message-input').prop('disabled', false);
                $('#send-button').prop('disabled', false);
            }, 1000);
        }
    });
});