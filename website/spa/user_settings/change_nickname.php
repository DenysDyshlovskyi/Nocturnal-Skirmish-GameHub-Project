<style><?php include "./css/nickname-upload.css" ?></style>
<style>
    #dark-container {
        display: block !important;
    }
</style>
<div class="settings-change-nickname-container" onload="prepareSFX()">
    <div class="settings-change-nickname-container-inner">
        <h1>Change nickname</h1>
        <input type="text" placeholder="New nickname" maxlength="25" id="change-nickname-input">
        <div class="settings-change-nickname-button-container">
            <button id="change-nickname-submit" onclick="saveNickname()">Submit</button>
            <button id="change-nickname-cancel" onclick="removeDarkContainer()">Cancel</button>
        </div>
    </div>
</div>