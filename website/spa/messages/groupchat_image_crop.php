<?php
// Crops the groupchat image that was uploaded to temp folder.

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
<!-- Reusing styling for profilepic crop -->
<style><?php include "../user_settings/css/profilepic-crop.css" ?></style>
<div class="settings-profilepic-crop-container">
    <div class="settings-profilepic-crop-save-container">
        <h1>Crop groupchat image</h1>
        <div class="settings-profilepic-crop-image-container">
            <img src="<?php echo $_SESSION['temp_groupchat_image']; ?>" id="cropper_js_element_groupchat">
        </div>
        <div class="settings-profilepic-crop-button-container">
            <button class="settings-profilepic-crop-save-button" id="groupchat-image-crop-save">Save</button>
            <button class="settings-profilepic-crop-save-button" id="settings-profilepic-crop-cancel-button" onclick="ajaxGet('./spa/messages/groupchat_settings.php', 'dark-container')">Cancel</button>
        </div>
    </div>
    <div class="settings-profilepic-preview-container">
        <div class="settings-profilepic-preview-profile">
            <h1>Preview</h1>
            <div class="groupchat-image-preview"></div>
        </div>
    </div>
</div>