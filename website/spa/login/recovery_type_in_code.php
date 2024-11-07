<!--Reusing styling for forgot_link.php-->
<style><?php include "./css/forgot-link.css" ?></style>
<?php session_start(); ?>
<style>
    #dark-container {
        display: block !important;
    }
</style>
<div class="forgot-link-container" id="type-in-code">
    <p>A code has been sent to <?php echo $_SESSION['temp_recovery_email']; ?>. Please type in the code within 5 minutes.</p>
    <input type="text" id="recovery_code_input" placeholder="000000" maxlength="6">
    <div class="forgot-link-button-container">
        <button class="forgot-link-next-button" onclick="recoveryCode()">Next</button>
        <button onclick="removeDarkContainer()" class="forgot-link-cancel-button">Cancel</button>
    </div>
</div>