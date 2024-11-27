<?php
// Really make sure the user has access to the groupchat
require "../../php_scripts/avoid_errors.php";

// Check if the current chat is a groupchat and if the user has access
if (isset($_SESSION['current_table'])) {
    // Does the user have access to the chat?
    $stmt = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND user_id = ?");
    $stmt->bind_param("ss", $_SESSION['current_table'], $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        echo "Error <button onclick='removeDarkContainer()'>Close</button>";
        exit;
    }
    $stmt->close();

    // Is the chat a groupchat?
    $stmt = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND type = 'groupchat'");
    $stmt->bind_param("s", $_SESSION['current_table']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        echo "Error <button onclick='removeDarkContainer()'>Close</button>";
        exit;
    }
    $stmt->close();
} else {
    echo "Error <button onclick='removeDarkContainer()'>Close</button>";
    exit;
}
?>
<style><?php include "./css/groupchat-settings.css" ?></style>
<div class="groupchat-settings-container">
    <div class="grouphcat-settings-edit-container">
        <a href="#" class="groupchat-image-link">
            <div class="groupchat-image" style="background-image: url(./img/groupchat_images/defaultgroupchat.svg);">
                <div class="groupchat-image-hover-pencil"></div>
            </div>
        </a>
        <input type="text" value="New Groupchat" maxlength="30" oninput="resizeGroupchatInput()" id="groupchat-name-input">
    </div>
    <div class="member-list-container">
        <h1 class="member-list-headline">Member List</h1>
        <div class="member-list">
            <div class="member-list-row">
                <div class="member-list-row-profilepic" style="background-image: url(./img/profile_pictures/defaultprofile.svg);">
                    <img src="./img/borders/defaultborder.webp" alt="">
                </div>
                <p class="member-list-nickname">BimBomSlimSlom</p>
            </div>
        </div>
    </div>
    <button class="groupchat-settings-close" onclick="removeDarkContainer()" title="Close groupchat settings">Close</button>
</div>