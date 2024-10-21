<!-- uses same styling as nickname upload -->
<style><?php include "./css/nickname-upload.css" ?></style>
<style>
    #settings-dark-container {
        display: block !important;
    }
</style>
<div class="settings-change-nickname-container" style="height: fit-content; padding-bottom: 15px;" onload="prepareSFX()">
    <div class="settings-change-nickname-container-inner">
        <h1>Change password</h1>
        <input type="password" placeholder="New password" maxlength="128" id="change-password-input">
        <input type="password" placeholder="Confirm new password" maxlength="128" id="change-password-confirm-input">
        <div class="settings-change-nickname-button-container">
            <button id="change-nickname-submit" onclick="savePassword()">Submit</button>
            <button id="change-nickname-cancel" onclick="removeDarkContainer()">Cancel</button>
        </div>
    </div>
</div>