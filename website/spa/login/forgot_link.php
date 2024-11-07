<style><?php include "./css/forgot-link.css" ?></style>
<style>
    #dark-container {
        display: block !important;
    }
</style>
<div class="forgot-link-container">
    <h1>Recover your username or password</h1>
    <p>Please type in the e-mail adress the account was registered with:</p>
    <input type="text" id="forgot-email-input" placeholder="E-mail">
    <div class="forgot-link-button-container">
        <button class="forgot-link-next-button" onclick="recoveryTypeIn()" id="type-in-email-next">Next</button>
        <button onclick="removeDarkContainer()" class="forgot-link-cancel-button">Cancel</button>
    </div>
</div>