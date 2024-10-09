<?php

require "./php_scripts/conn.php";

$showError = false;
$errorMessage = "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Log in</title>
    <style> <?php include "./css/login-page.css" ?> </style>
    <style> <?php include "./css/universal.css" ?> </style>
</head>
<body>
    <a href="./phpMyAdmin/index.php" class="login-admin-link">Admin</a>
    <div class="login-container">
        <div class="login-logo-container">
            <img src="" alt="">
        </div>
        <div class="login-form-container">
            <form action="index.php" method="POST" class="login-form">
                <h1>Log in to GameHub</h1>
                <div class="login-error-container">
                    <?php echo $errorMessage; ?>
                </div>
                <input type="text" placeholder="Username" required class="login-cred-input" maxlength="50">
                <br>
                <input type="text" placeholder="Password" required class="login-cred-input" maxlength="80">
                <p class="login-register-link">Dont have a user? <a href="create_account.php">Create account.</a></p>
                <input type="submit" value="Log in" class="login-button">
                <br>
                <a href="" class="login-forgot-link">Forgot username or password?</a>
            </form>
        </div>
    </div>
</body>
</html>

<?php
if ($showError == true) {
    echo "<style>.login-error-container{display: block;}</style>";
};
?>