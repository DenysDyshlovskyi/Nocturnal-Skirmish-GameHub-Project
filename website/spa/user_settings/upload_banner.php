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
        <label for="banner-input" class="settings-upload-banner-input">
            Upload
        </label>
        <input type="file" id="banner-input" accept="image/jpeg" onchange="filePreview(this)">
    </div>
    <p>Preview:</p>
    <div class="settings-upload-banner-preview" id="bannerPreview"></div>
    <div class="settings-upload-banner-button-container">
        <button>Submit</button>
        <button onclick="removeDarkContainer()" id="settings-upload-banner-button-cancel">Cancel</button>
    </div>
</div>