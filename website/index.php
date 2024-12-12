<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub & Nocturnal Skirmish - Log in</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style> <?php include "./css/login-page.css" ?> </style>
    <style> <?php include "./css/universal.css" ?> </style>
</head>
<body>
    <div id="wait-container" class="wait-container">
        <div class="wait-container-inner">Please wait...</div>
    </div>
    <div id="dark-container" class="dark-container"></div>
    <div class="confirmation-popup" id="confirmContainer"></div>
    <a href="./admin_centre/admin_login.php" class="login-admin-link">Admin</a>
    <div class="login-container">
        <div class="login-logo-container">
            <img src="./img/Noc_Skir_Logo.svg" alt="Nocturnal Skirmish Logo" draggable="false">
        </div>
        <div class="login-form-container">
            <div class="login-form-inner">
                <h1>Log in to GameHub</h1>
                <input type="text" placeholder="Username" class="login-cred-input" maxlength="25" id="username-input" onkeydown = "if (event.keyCode == 13){ loginForm() }">
                <div class="password-input-wrapper">
                    <input type="password" placeholder="Password" maxlength="80" id="password-input" onkeydown = "if (event.keyCode == 13){ loginForm() }">
                    <button id="password-view-button" onclick="changeVisibility('password-input', 'password-view-button')"></button>
                </div>
                <p class="login-register-link">Dont have a user? <a href="create_account.php">Create account.</a></p>
                <input type="submit" value="Log in" class="login-button" onclick="loginForm()">
                <br>
                <a href="#" class="login-forgot-link" onclick="ajaxGet('./spa/login/forgot_link.php', 'dark-container', 'no_sfx')">Forgot username or password?</a>
            </div>
        </div>
    </div>
    <button class="login-info-button" title="Tutorial" onclick="window.location.href = 'tutorial.php?section=gamehub'"></button>
</body>
<script type="text/javascript"><?php include "./js/script.js" ?></script>
</html>