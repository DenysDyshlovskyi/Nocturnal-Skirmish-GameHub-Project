<?php
// Crops the banner that was uploaded to temp folder.
session_start();
?>
<!-- Reusing styling for profilepic crop -->
<style><?php include "./css/profilepic-crop.css" ?></style>
<style>
    #dark-container {
        display: block !important;
    }
</style>
<div class="settings-profilepic-crop-container">
    <div class="settings-profilepic-crop-save-container">
        <h1>Crop banner</h1>
        <div class="settings-profilepic-crop-image-container">
            <img src="<?php echo $_SESSION['temp_profile_banner']; ?>" id="cropper_js_element_banner" alt="">
        </div>
        <div class="settings-profilepic-crop-button-container">
            <button class="settings-profilepic-crop-save-button" id="settings-banner-crop-save-button">Save</button>
            <button class="settings-profilepic-crop-save-button" id="settings-profilepic-crop-cancel-button" onclick="removeDarkContainer()">Cancel</button>
        </div>
    </div>
    <div class="settings-profilepic-preview-container">
        <div class="settings-profilepic-preview-profile">
            <h1>Preview</h1>
            <div class="settings-banner-preview-banner"></div>
        </div>
    </div>
</div>