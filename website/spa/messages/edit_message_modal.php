<?php
    require "../../php_scripts/avoid_errors.php";
    // Ui for editing message

    // Get information about the message
    $conn -> select_db("gamehub_messages");
    $tablename = $_SESSION['current_table'];

    $stmt = $conn->prepare("SELECT * FROM $tablename WHERE message_id = ?");
    $stmt->bind_param("s", $_SESSION['editmessage_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);

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

    // Checks if message is reply to diffrent message. If it is, add reply-message div
    if ($row['reply'] != 0) {
        // Get information about message being replied to.
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

        $replyMessage = "<div class='reply-message-container'>
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

    // Saves the message to later be outputted
    $toedit_message = "<div class='message-container'>
            $replyMessage
            <div class='message-name-container'>
                <a href='#'>
                    <div class='message-profilepic' style='background-image: url(" . $_SESSION['user_profile_picture'] . ");'>
                        <img src='" . $_SESSION['user_profile_border'] . "'>
                    </div>
                </a>
                <h1 class='message-nickname'>" . $_SESSION['user_profile_nickname'] . " - <i>" . $row['timestamp'] . " $edited</i></h1>
            </div>
            <div class='message-content' style='background-color: #FFCF8C;' id='edit-message-modal-message'>
                <textarea spellcheck='false' id='edit-message-textarea' maxlength='500' oninput='resizeTextAreaEdit()'>$message</textarea>$br
                $mediaAttachment
            </div>
        </div>";
?>

<!-- Reusing styling for delete message modal -->
<style><?php include "./css/delete-message-modal.css" ?></style>
<div class="delete-message-modal-container">
    <h1 class="delete-message-modal-headline">Edit message</h1>
    <i class="delete-message-modal-warning">Click the message to edit the text.</i>
    <div class="delete-message-modal-message-container">
        <?php echo $toedit_message ?>
    </div>
    <div class="delete-message-modal-button-container">
        <button onclick="confirmEditMessage(<?php echo $_SESSION['editmessage_id'] ?>)" id="save-button">Save</button>
        <button onclick="removeDarkContainer()" id="cancel-button">Cancel</button>
    </div>

</div>