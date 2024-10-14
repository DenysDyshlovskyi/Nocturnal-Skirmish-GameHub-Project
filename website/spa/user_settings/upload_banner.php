<style><?php include "./css/banner-upload.css" ?></style>
<style>
    #settings-dark-container {
        display: block !important;
    }
</style>
<div class="settings-upload-banner-container">
    <h1>Upload banner image</h1>
    <p>Aspect ratio for banners is 93:14.</p>
    <p>Only supports JPG.</p>
    <div class="settings-upload-banner-input-container">
        <form method="POST" action="" enctype="multipart/form-data" id="banner-upload-form">
            <label for="banner-input" class="settings-upload-banner-input">
                Upload
            </label>
            <input type="file" id="banner-input" accept="image/jpeg" onchange="filePreview(this)">
        </form>
    </div>
    <p>Preview:</p>
    <div class="settings-upload-banner-preview" id="bannerPreview"></div>
    <div class="settings-upload-banner-button-container">
        <button onclick="ajaxPostImage('#banner-input', './php_scripts/banner_image_upload.php', 'Banner image uploaded!')">Submit</button>
        <button onclick="removeDarkContainer()" id="settings-upload-banner-button-cancel" form="banner-upload-form">Cancel</button>
    </div>
</div>