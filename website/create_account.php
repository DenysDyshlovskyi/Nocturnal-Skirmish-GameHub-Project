<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Create account</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/create-account-page.css" ?> </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body onload="validateUsername('')">
    <div id="wait-container" class="wait-container">
        <div class="wait-container-inner">Please wait...</div>
    </div>
    <div class="confirmation-popup" id="confirmContainer"></div>
    <div class="ca-container">
        <h1 class="ca-headline">Create a GameHub account.</h1>
        <div class="ca-creation-form-container">
            <div class="ca-creation-form-user-details-container">
                <h1 class="ca-form-headline">User details</h1>
                <input type="text" placeholder="Username*" id="username-input" class="ca-creation-cred-input" maxlength="25" oninput="validateUsername(this.value)">
                <div class="username-validate-container" id="username-validate-container">
                </div>
                <input type="text" placeholder="Nickname*" id="nickname-input" class="ca-creation-cred-input" maxlength="25">
                <br>
                <textarea id="description-input" placeholder="Description" class="ca-creation-description-input" maxlength="500"></textarea>
            </div>
            <div class="ca-creation-form-additional-information-container">
                <h1 class="ca-form-headline">Additional information</h1>
                <input type="email" placeholder="E-mail*" id="email-input" required class="ca-creation-cred-input" maxlength="128">
                <br>
                <input type="email" placeholder="Confirm e-mail*" id="email-input-confirm" required class="ca-creation-cred-input" maxlength="128">
                <br>
                <div class="password-input-wrapper">
                    <input type="password" placeholder="Password*" id="password-input" required class="ca-creation-cred-input" maxlength="80">
                    <button id="password-view-button" onclick="changeVisibility('password-input', 'password-view-button')"></button>
                </div>
                <div class="password-input-wrapper">
                    <input type="password" placeholder="Confirm password*" id="password-input-confirm" required class="ca-creation-cred-input" maxlength="80">
                    <button id="password-view-confirm-button" onclick="changeVisibility('password-input-confirm', 'password-view-confirm-button')"></button>
                </div>
                <input type="checkbox" required id="terms-checkbox">*I agree to GameHub's <a href="terms_and_conditions.txt" class="ca-terms-link">terms and conditions.</a>
                <p class="ca-username-indefinite-warning">*You can always change your nickname, but the username is indefinite</p>
                <span id="ca-login-button-span"><button class="ca-next-button" onclick="location.href = 'index.php'">Back to login</button></span>
                <span><input type="submit" value="Create account" class="ca-next-button" name="next_button" onclick="waitClick(); createAccount();"></span>
            </div>
        </div>
    </div>
</body>
<script type="text/javascript"><?php include "./js/script.js" ?></script>
</html>