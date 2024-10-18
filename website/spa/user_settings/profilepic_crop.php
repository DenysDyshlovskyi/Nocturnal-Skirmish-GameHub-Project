<?php
// Crops the profilepic that was uploaded to temp folder.
session_start();
?>
<style><?php include "./css/profilepic-crop.css" ?></style>
<style>
    #settings-dark-container {
        display: block !important;
    }
</style>
<div class="settings-profilepic-crop-container">
    <div class="settings-profilepic-crop-save-container">
        <h1>Crop profile picture</h1>
        <div class="settings-profilepic-crop-image-container">
            <img src="<?php echo $_SESSION['temp_profile_pic']; ?>" id="cropper_js_element" alt="">
        </div>
        <div class="settings-profilepic-crop-button-container">
            <button class="settings-profilepic-crop-save-button" id="settings-profilepic-crop-save-button">Save</button>
            <button class="settings-profilepic-crop-save-button" id="settings-profilepic-crop-cancel-button" onclick="removeDarkContainer()">Cancel</button>
        </div>
    </div>
    <div class="settings-profilepic-preview-container">
        <div class="settings-profilepic-preview-profile">
            <h1>Preview</h1>
            <div class="settings-profilepic-preview-profile-parent">
                <div class="settings-profilepic-preview-profilepic"></div>
                <img src="<?php echo $_SESSION['user_profile_border'] ?>" alt="Profile Border" class="settings-profilepic-preview-profile-border" draggable="false">
            </div>
        </div>
    </div>
</div>