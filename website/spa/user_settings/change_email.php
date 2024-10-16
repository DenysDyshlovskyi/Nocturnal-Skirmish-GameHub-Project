<!-- uses same styling as nickname upload -->
<style><?php include "./css/nickname-upload.css" ?></style>
<style>
    #settings-dark-container {
        display: block !important;
    }
</style>
<div class="settings-change-nickname-container">
    <div class="settings-change-nickname-container-inner">
        <h1>Change email</h1>
        <input type="text" placeholder="New email" maxlength="128" id="change-email-input">
        <div class="settings-change-nickname-button-container">
            <button id="change-nickname-submit" onclick="saveEmail()">Submit</button>
            <button id="change-nickname-cancel" onclick="removeDarkContainer()">Cancel</button>
        </div>
    </div>
</div>