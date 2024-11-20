<?php
// Loads messages from current table.
require "avoid_errors.php";
start:

if (!isset($_SESSION['current_table'])) {
    // If a chat has not been selected
    echo "<p class='select-to-begin'>Select a chat to begin</p>";
} else {
    $conn -> select_db("gamehub_messages");
    $tablename = $_SESSION['current_table'];

    // Check if table exists
    $stmt = $conn->prepare("SELECT table_name FROM information_schema.tables WHERE table_schema = 'gamehub_messages' AND table_name = '$tablename';");
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        //Table doesnt exist
        unset($_SESSION['current_table']);
        goto start;
    }
    $stmt->close();

    $message_amount = $_SESSION['message_amount'];

    $stmt = $conn->prepare("SELECT *
FROM (
    SELECT *
    FROM $tablename
    ORDER BY message_id DESC
    LIMIT $message_amount
) AS latest_posts
ORDER BY message_id ASC;");
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows != 0){
        while ($row = mysqli_fetch_assoc($result)) {
            // Gets information about user who sent the current message
            $conn -> select_db("gamehub");
            $stmt2 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt2->bind_param("s", $row['user_id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = mysqli_fetch_assoc($result2);
            // Make message orange if its sent by you, and adds extra buttons on hover for deleting and editing message
            if ($row['user_id'] == $_SESSION['user_id']) {
                $backgroundColor = "style='background-color: #FFCF8C;'";
                $moreButtons = "<button id='delete-message-button' onclick='deleteMessage(" . $row['message_id'] . ")'></button>
                                <button id='edit-message-button' onclick='editMessage(" . $row['message_id'] . ")'></button>";
            } else {
                $backgroundColor = "";
                $moreButtons = "";
            }

            // if row has image attached, show it.
            if($row['file'] != NULL) {
                $mediaAttachment = "<a target='_blank' href='./img/chat_images/" . $row['file'] . "'><img class='message-media-attachment' src='./img/chat_images/" . $row['file'] . "'></a>";

                // If message has text, add a breakline to put the image under the text
                if (strlen($row['message']) > 0) {
                    $br = "<br>";
                } else {
                    $br = "";
                }
            } else {
                $mediaAttachment = "";
                $br = "";
            }

            // Prepares nickname to put inside replyToMessage parameter
            $nickname = '"' . $row2['nickname'] . '"';

            // Checks if message is reply to diffrent message. If it is, add reply-message div
            if ($row['reply'] != 0) {
                // Get information about message being replied to.
                $conn -> select_db("gamehub_messages");
                $stmt3 = $conn->prepare("SELECT message, user_id, file FROM $tablename WHERE message_id = ?");
                $stmt3->bind_param("s", $row['reply']);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
                if($result3->num_rows === 0){
                    // If the message was deleted
                    $replyMessage = "<div class='reply-message-container' title='Message was deleted'>
                        <img src='./img/icons/reply_mirror.svg' class='reply-message-arrow'>
                        <div class='reply-message-profilepic' style='background-image: url(./img/profile_pictures/defaultprofile.svg);'>
                            <img src='./img/borders/defaultborder.webp'>
                        </div>
                        <div class='reply-message-name-container'>
                            <h1>Deleted Message</h1>
                            <div class='reply-message-content-container'>
                                <p>Deleted Message</p>
                            </div>
                        </div>
                    </div>";
                    goto reply_end;
                }
                $reply_message_row = mysqli_fetch_assoc($result3);

                // Get information about user who sent the message
                $conn -> select_db("gamehub");
                $stmt4 = $conn->prepare("SELECT nickname, profile_border, profile_picture FROM users WHERE user_id = ?");
                $stmt4->bind_param("s", $reply_message_row['user_id']);
                $stmt4->execute();
                $result4 = $stmt4->get_result();
                $reply_message_user_row = mysqli_fetch_assoc($result4);

                // If message has image attached, add image icon
                if ($reply_message_row['file'] != NULL) {
                    $replyImageAttachment = "<img src='./img/icons/image.svg'>";
                } else {
                    $replyImageAttachment = "";
                };

                $replyMessage = "<div class='reply-message-container' title='Scroll to message' onclick='messageScroll(" . $row['reply'] . ")'>
                        <img src='./img/icons/reply_mirror.svg' class='reply-message-arrow'>
                        <div class='reply-message-profilepic' style='background-image: url(./img/profile_pictures/" . $reply_message_user_row['profile_picture'] . ");'>
                            <img src='./img/borders/" . $reply_message_user_row['profile_border'] . "'>
                        </div>
                        <div class='reply-message-name-container'>
                            <h1>" . $reply_message_user_row['nickname'] . "</h1>
                            <div class='reply-message-content-container'>
                                <p>" . $reply_message_row['message'] . "</p>
                                $replyImageAttachment
                            </div>
                        </div>
                    </div>";
            } else {
                $replyMessage = "";
            }

            reply_end:

            // If message was edited, add (edited) after timestamp
            if ($row['edited'] == 1) {
                $edited = "(edited)";
            } else {
                $edited = "";
            }

            //If there is a link in the message, wrap it in <a> tag;
            $text = strip_tags($row['message']);
            $textWithLinks = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank" rel="nofollow">$1</a>', $text);
            $message = $textWithLinks;

            // Outputs the message to the screen
            echo "<div onmouseover='showMessageButtons(" . $row['message_id'] .  ")' onmouseout='hideMessageButtons(" . $row['message_id'] .  ")' class='message-container' id='" . $row['message_id'] . "'>
                    <div class='message-buttons-container' id='" . $row['message_id'] . "_ButtonContainer'>
                        <div class='message-buttons-relative'>
                            <button title='Reply to message' id='reply-message-button' onclick='replyToMessage(" . $row['message_id'] . ", $nickname)'></button>
                            $moreButtons
                        </div>
                    </div>
                    $replyMessage
                    <div class='message-name-container'>
                        <a href='#' onclick='displayUserProfile(" . $row2['user_id'] . ")'>
                            <div class='message-profilepic' style='background-image: url(./img/profile_pictures/" . $row2['profile_picture'] . ");'>
                                <img src='./img/borders/" . $row2['profile_border'] . "'>
                            </div>
                        </a>
                        <h1 class='message-nickname'>" . $row2['nickname'] . " - <i>" . $row['timestamp'] . " $edited</i></h1>
                    </div>
                    <div class='message-content' $backgroundColor>
                        <p>$message</p>$br
                        $mediaAttachment
                    </div>
                </div>";
        }
    } else {
        echo "<p class='select-to-begin'>Looks like this chat is empty. Send a message to start the conversation!</p>";
    }
    $stmt->close();
    $conn -> select_db("gamehub");
}