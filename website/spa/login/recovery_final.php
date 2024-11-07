<style><?php include "./css/recovery-final.css" ?></style>
<?php session_start(); ?>
<style>
    #dark-container {
        display: block !important;
    }
</style>
<div class="recovery-final-container">
    <h1>The username for this account is <?php echo $_SESSION['temp_recovery_username'] ?></h1>
    <div class="new-password-input-container" id="new-password-input-container">
        <p>Create new password</p>
        <input type="text" id="new-password-input" placeholder="New password" maxlength="80">
        <br>
        <input type="text" id="new-password-input-confirm" placeholder="Confirm new password" maxlength="80">
    </div>
    <div class="recovery-final-button-container">
        <button class="recovery-final-password-button" onclick="showNewPassword()" id="password-recovery-button">Recover password</button>
        <button class="recovery-final-done-button" onclick="removeDarkContainer()">Done</button>
    </div>
</div>