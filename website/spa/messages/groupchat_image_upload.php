<!-- Reusing styling for banner upload -->
<style><?php include "../user_settings/css/banner-upload.css" ?></style>
<div class="settings-upload-banner-container">
    <h1>Upload groupchat image</h1>
    <p>Only supports JPG and PNG.</p>
    <div class="settings-upload-banner-input-container">
        <form method="POST" action="" enctype="multipart/form-data" id="banner-upload-form">
            <label for="groupchat-image-input" class="settings-upload-banner-input">
                Upload
            </label>
            <input type="file" id="groupchat-image-input" accept="image/jpeg, image/png" onchange="uploadGroupchatImage()">
        </form>
    </div>
    <div class="settings-upload-banner-button-container">
        <button onclick="ajaxGet('./spa/messages/groupchat_settings.php', 'dark-container');" id="settings-upload-banner-button-cancel">Cancel</button>
    </div>
</div>