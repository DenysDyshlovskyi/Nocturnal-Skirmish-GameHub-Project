// Contains some JavaScript for messages.php, script tag was getting too cluttered.

        // Hides or shows menu for attaching media or file
        function hideShowAttachMenu() {
            var menu = document.getElementById("more-button-popup");
            if (window.getComputedStyle(menu).display === 'none'){
                if (document.getElementById('media-file-input').value == "") {
                    $("#more-button-popup").fadeIn(100);
                    menu.style.display = 'block';
                } else {
                    showConfirm("Cannot add 2 attachments at the same time.")
                }
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

        // Resizes message edit input based on how much text is inside of it
        function resizeTextAreaEdit() {
            var textarea = document.getElementById("edit-message-textarea");

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

        // Starts 5 second interval to update players online counter, and to check if user should be kicked, and to update total amount of new messages in tab title
        setInterval(function(){
            ajaxGet('./php_scripts/update_login_time.php', 'players-live-count', 'no_sfx');
            ajaxGet('./php_scripts/update_new_messages_tab_title.php', 'tab-title', 'no_sfx');
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

        // Starts 4.5 second interval to update notification
        setInterval(function(){
            ajaxGet('./php_scripts/load_chat_list.php', 'messages-menu-chats-container');
        }, 4500);

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
                ajaxGet('./php_scripts/load_chat_list.php', 'messages-menu-chats-container');
                ajaxGet('./php_scripts/update_new_messages_tab_title.php', 'tab-title', 'no_sfx');
                document.getElementById("message-input").value = '';
                removeMedia();
                cancelReply();
                resizeMessageBar();
                setTimeout(scrollToBottom, 100);
                if (tablename == "public") {
                    document.getElementById("public-chat-button").style.border = "solid 2px black";
                } else {
                    document.getElementById("public-chat-button").style.border = "none";
                }
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
    formData.append('reply', replyingToMessageId);

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
            } else if (response == "unsupported") {
                showConfirm("Unsupported file format! Only JPG, PNG, GIF or WebP allowed!");
                document.getElementById("message-input").value = '';
            } else if (response == "error") {
                showConfirm("Something went wrong.");
                document.getElementById("message-input").value = '';
            } else if (response == "toolarge") {
                showConfirm("Attachment exceeds 3MB! Please upload smaller file.")
                removeMedia();
            } else if (response == "toolong") {
                showConfirm("Message is too long! Character limit is 500.");
                document.getElementById("message-input").value = '';
            } else {
                ajaxGet("./php_scripts/load_messages.php", "messages-container", "scroll");
                document.getElementById("message-input").value = '';
                removeMedia();
                cancelReply();
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

// Shows buttons on messages for for example replying when hovering over specific message
function showMessageButtons(message_id) {
    var buttonContainer = document.getElementById(message_id + '_ButtonContainer');
    buttonContainer.style.display = "block";
}

// Hides buttons on messages for for example replying when hovering over specific message
function hideMessageButtons(message_id) {
    var buttonContainer = document.getElementById(message_id + '_ButtonContainer');
    buttonContainer.style.display = "none";
}

// Sets global variable of message id person is replying to
var replyingToMessageId = 0;

// Lets the system know that the message thats about to be sent is a reply, and show div containing nickname of who youre replying to
function replyToMessage(message_id, nickname) {
    var replyingToContainer = document.getElementById("replyingto-container");
    var replyingToPTag = document.getElementById("replyingto-p");
    replyingToPTag.innerHTML = "Replying to " + nickname;
    replyingToContainer.style.display = "flex";
    replyingToMessageId = message_id;
    resizeMessageBar();
}

// Cancels replying to message
function cancelReply() {
    var replyingToContainer = document.getElementById("replyingto-container");
    var replyingToPTag = document.getElementById("replyingto-p");
    replyingToContainer.style.display = "none";
    replyingToPTag.innerHTML = "";
    replyingToMessageId = 0;
    resizeMessageBar();
}

//Loads in message and scrolls to it
function messageScroll(messageID) {
    // Check if message exists first
    $.ajax({
        type: "POST",
        url: './php_scripts/does_message_exist.php',
        data:{ messageID : messageID }, 
        success: function(response){
            if (response == "doesntexist") {
                showConfirm("Message doesnt exist!");
            } else {
                if (document.getElementById(messageID) == null) {
                    var isMessageLoaded = setInterval(function(){
                        if(document.getElementById(messageID) == null){
                            $.get('./php_scripts/load_more_messages.php');
                            ajaxGet("./php_scripts/load_messages.php", "messages-container");
                        } else {
                            clearInterval(isMessageLoaded);
                            jumpToMessage(messageID);
                        }
                    }, 200);
                } else {
                    jumpToMessage(messageID);
                }
            }
        }
    })
};

// Jumps to message with id in parameter, and highlights it
function jumpToMessage(messageID){
    var i=0;
    document.getElementById(messageID).scrollIntoView();
    var highlighInterval = setInterval(function(){
        i++;
        if (i<45){
            document.getElementById(messageID).style.backgroundColor = "rgba(0, 0, 0, 0.25)";
        } else {
            clearInterval(highlighInterval);
        }
    }, 25);
}

// Shows confirmation of deleting message
function deleteMessage(message_id) {
    $.ajax({
        type: "POST",
        url: './php_scripts/delete_message.php',
        data:{ message_id : message_id }, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.");
            } else {
                ajaxGet("./spa/messages/delete_message_modal.php", "dark-container");
            }
        }
    })
}

// Deletes message after user has confirmed deletion of message
function confirmDeleteMessage(message_id) {
    $.ajax({
        type: "POST",
        url: './php_scripts/confirm_delete_message.php',
        data:{ message_id : message_id }, 
        success: function(response){
            if (response == "error") {
                removeDarkContainer();
                showConfirm("Something went wrong.");
            } else {
                document.getElementById(message_id).remove();
                removeDarkContainer();
                showConfirm("Message deleted.")
            }
        }
    })
}

// Shows ui for editing message
function editMessage(message_id) {
    $.ajax({
        type: "POST",
        url: './php_scripts/edit_message.php',
        data:{ message_id : message_id }, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.");
            } else {
                ajaxGet("./spa/messages/edit_message_modal.php", "dark-container");
            }
        }
    })
}

// Saves message after user clicks save
function confirmEditMessage(message_id) {
    var message = document.getElementById("edit-message-textarea").value
    $.ajax({
        type: "POST",
        url: './php_scripts/confirm_edit_message.php',
        data:{ message_id : message_id, message : message }, 
        success: function(response){
            if (response == "empty") {
                showConfirm("Message is empty!");
            } else if (response == "error") {
                showConfirm("Something went wrong.");
            } else if (response == "toolong") {
                showConfirm("Message is too long! Character limit is 500.");
            } else {
                ajaxGet("./php_scripts/load_messages.php", "messages-container");
                removeDarkContainer();
                showConfirm("Message edited.")
            }
        }
    })
}

// Animation for new chat dropdown
function newChatDropdown() {
    dropdownContainer = document.getElementById("new-chat-dropdown");
    dropdownButton = document.getElementById("messages-add-chat-button");
    $('#messages-add-chat-button').prop('disabled', true);
    if (window.getComputedStyle(dropdownContainer).display === 'none'){
        dropdownButton.style.backgroundImage = "url()";
        dropdownButton.innerHTML = "Cancel";
        dropdownContainer.style.display = "flex";
        dropdownContainer.style.height = "0px";
        var heightPx = 0;
        dropdownInterval = setInterval(function(){
            heightPx = heightPx + 4;
            dropdownContainer.style.height = heightPx + "px";
            if (heightPx >= 60) {
                clearInterval(dropdownInterval);
                $('#messages-add-chat-button').prop('disabled', false);
            }
        }, 1)
    } else {
        dropdownButton.style.backgroundImage = "url(./img/icons/plus.svg)";
        dropdownButton.innerHTML = "";
        dropdownContainer.style.height = "60px";
        var heightPx = 60;
        dropdownInterval = setInterval(function(){
            heightPx = heightPx - 4;
            dropdownContainer.style.height = heightPx + "px";
            if (heightPx <= 0) {
                clearInterval(dropdownInterval);
                dropdownContainer.style.display = 'none';
                $('#messages-add-chat-button').prop('disabled', false);
            }
        }, 1)
    }
}

// Shows ui for creating a new groupchat or private message
function createChatUi(mode) {
    dropdownButton = document.getElementById("messages-add-chat-button");
    dropdownContainer = document.getElementById("new-chat-dropdown");
    
    dropdownButton.style.backgroundImage = "url(./img/icons/plus.svg)";
    dropdownButton.innerHTML = "";
    dropdownContainer.style.height = "60px";
    dropdownContainer.style.display = "none";

    if (mode == "groupchat") {
        ajaxGet("./spa/messages/create_groupchat.php", "dark-container");
    } else if (mode == "pm") {
        ajaxGet("./spa/messages/create_pm.php", "dark-container");
    } else {
        showConfirm("Something went wrong.");
    }
}

// Function to highlight parent div of checkbox when checkbox is clicked
function highlightCheckbox(elementID) {
    var checkbox = document.getElementById(elementID);
    var parentDiv = checkbox.parentNode;

    checkbox = "#" + elementID;

    if ($(checkbox).is(':checked')) {
        parentDiv.style.backgroundColor = "#4a89ff40";
    } else {
        parentDiv.style.backgroundColor = "#F1F1F1";
    }
}

// Function for searching through friend list
// Did the search function in pure javascript instead of post in ajax with SQL LIKE queries. This is because using ajax post and returning the
// result in the result container would completely replace the child elements. So if a checkbox was checked, and you did a search, that checkbox
// would no longer be checked, because everything got completely replaced.
function createGroupchatSearch(search) {
    // Deletes none found element from previous query if it exists
    if ($('#none-found-p').length > 0) {
        document.getElementById('none-found-p').remove();
    }
    const resultContainer = document.getElementById('create-groupchat-result-container');

    // Select all elements in result container
    const allElements = document.getElementById('create-groupchat-result-container').children;

    // Convert to lower case to be case insensitive
    const searchLower = search.toLowerCase();

    // Hides all elements in result container
    Array.from(allElements).forEach(
        el => el.style.display = "none"
    )
    
    // Filter the elements based on the search
    const filteredElements = Array.from(allElements).filter(el =>
        el.id.toLowerCase().includes(searchLower)
    );

    // Checks if any nicknames with the search query were found
    if (filteredElements.length === 0) {
        // Not found
        // Create p tag that says that the search returned to results
        const para = document.createElement("p");
        para.id = "none-found-p";
        const node = document.createTextNode("No nicknames found containing '" + search + "'.");

        // Put the p tag inside result container
        para.appendChild(node);
        resultContainer.appendChild(para);
    } else {
        // Found
        // Show the elements that have been filtered
        filteredElements.forEach(
            el => el.style.display = "flex"
        )
    }
}

// When form for creating groupchat is submitted
function createGroupchat() {
    var formData = new FormData($('#create-groupchat-form')[0]);
    $.ajax({
        url: './php_scripts/create_groupchat.php',
        type: 'POST',
        data: formData,
        success: function (response) {
            if (response == "error") {
                removeDarkContainer();
                showConfirm("Something went wrong.")
            } else if (response == "short") {
                showConfirm("You have to select atleast 2 friends!")
            } else if (response == "empty") {
                showConfirm("No friends selected!")
            } else {
                removeDarkContainer();
                ajaxGet("./php_scripts/load_messages.php", "messages-container");
                ajaxGet("./php_scripts/load_current_messenger.php", "current-messenger-container");
                ajaxGet('./php_scripts/load_chat_list.php', 'messages-menu-chats-container');
                document.getElementById("message-input").value = '';
                removeMedia();
                cancelReply();
                resizeMessageBar();
                showConfirm("Groupchat created!");
            }
        },
        cache: false,
        contentType: false,
        processData: false,
    });
};

// Opens groupchat settings
function openGroupchatSettings(groupchat) {
    $.ajax({
        type: "POST",
        url: './php_scripts/open_groupchat_settings.php',
        data:{ groupchat : groupchat }, 
        success: function(response){
            if (response == "error") {
                removeDarkContainer();
                showConfirm("Something went wrong.")
            } else if (response == "not_groupchat") {
                removeDarkContainer();
                showConfirm("This chat is not a groupchat!")
            } else {
                ajaxGet('./spa/messages/groupchat_settings.php', 'dark-container');
            }
        }
    })
}