<?php
$showError = false;
$errorMessage = "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Create account</title>
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/create-account-page.css" ?> </style>
</head>
<body>
    <div class="ca-container">
        <h1 class="ca-headline">Create a GameHub account.</h1>
        <div class="ca-creation-form-container">
            <form action="create_account.php" method="POST" class="ca-creation-form">
                <div class="ca-creation-form-user-details-container">
                    <h1 class="ca-form-headline">User details</h1>
                    <input type="text" placeholder="Username" required class="ca-creation-cred-input" maxlength="50">
                    <br>
                    <input type="text" placeholder="Nickname*" required class="ca-creation-cred-input" maxlength="50">
                    <br>
                    <textarea name="" id="" placeholder="Description" class="ca-creation-description-input" maxlength="500"></textarea>
                </div>
                <div class="ca-creation-form-additional-information-container">
                    <h1 class="ca-form-headline">Additional information</h1>
                    <input type="email" placeholder="E-mail" required class="ca-creation-cred-input" maxlength="128">
                    <br>
                    <input type="email" placeholder="Confirm e-mail" required class="ca-creation-cred-input" maxlength="128">
                    <br>
                    <input type="password" placeholder="Password" required class="ca-creation-cred-input" maxlength="80">
                    <br>
                    <input type="password" placeholder="Confirm password" required class="ca-creation-cred-input" maxlength="80">
                    <br>
                    <input type="checkbox" required>I agree to GameHub's <a href="" class="ca-terms-link">terms and conditions.</a>
                    <p class="ca-username-indefinite-warning">*You can always change your nickname, but the username is indefinite</p>
                    <div class="ca-creation-error-container">
                        <?php echo $errorMessage; ?>
                    </div>
                    <span><input type="submit" value="Next" class="ca-next-button"></span>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<?php
if ($showError == true) {
    echo "<style>.ca-creation-error-container{display: block;}</style>";
};
?>