<style><?php include "./css/banner-upload.css" ?></style>
<style>
    #dark-container {
        display: block !important;
    }
</style>
<div class="settings-upload-banner-container" onload="prepareSFX()">
    <h1>Upload banner</h1>
    <p>Only supports JPG.</p>
    <div class="settings-upload-banner-input-container">
        <form method="POST" action="" enctype="multipart/form-data" id="banner-upload-form">
            <label for="banner-input" class="settings-upload-banner-input">
                Upload
            </label>
            <input type="file" id="banner-input" accept="image/jpeg" onchange="uploadBanner()">
        </form>
    </div>
    <div class="settings-upload-banner-button-container">
        <button onclick="removeDarkContainer()" id="settings-upload-banner-button-cancel" form="banner-upload-form">Cancel</button>
    </div>
</div>