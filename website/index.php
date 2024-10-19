<?php
session_start();
require "./config/conn.php";
require "./lib/mail.php";

// Defines variables for later use
$showError = false;
$errorMessage = "";
$mailReceiver = "";
$tempRecoveryUsername = "";
$skip = false;
$newPasswordSaved = false;
$newPasswordError = false;

if(!empty($_POST['login_button'])) {
    // SQL code to retreive user from database that matches with input
    // The ? below are parameter markers used for variable binding
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $_POST['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        $showError = true;
        $errorMessage = "User does not exist!";
    } else {
        $row = $result->fetch_assoc();
        if (password_verify($_POST['password'], $row['password'])){
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: ./hub.php");
        } else {
            $showError = true;
            $errorMessage = "Wrong password!";
        };
    };
};

if(!empty($_POST['next_recovery'])) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $_POST['email_recovery']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        $showError = true;
        $errorMessage = "Email not registered!";
        $skip = true;
    } else {
        $row = $result->fetch_assoc();

        $randomCode = random_int(100000, 999999);

        $sql = "INSERT INTO recovery_codes (user_id, code, expire) VALUES (" . $row['user_id'] . ", $randomCode, NOW() + INTERVAL 5 MINUTE)";
        mysqli_query($conn,$sql);

        $_SESSION['temp_recovery_userid'] = $row['user_id'];
        $_SESSION['temp_recovery_username'] = $row['username'];

        $mailReceiver = $_POST['email_recovery'];
        $mailSubject = "GameHub account recovery";
        $mailBody = "Hey " . $_POST['email_recovery'] . ". The recovery code is: $randomCode";
        $mailBodyAlt = "Hey " . $_POST['email_recovery'] . ". The recovery code is: $randomCode";
        sendMail($mailReceiver, $mailSubject, $mailBody, $mailBodyAlt);
    }
};

if(!empty($_POST['code_input_button'])) {
    // Removes expired recovery codes from database
    $sql = "DELETE FROM recovery_codes WHERE expire < NOW()";
    $conn->query($sql);

    $stmt = $conn->prepare("SELECT * FROM recovery_codes WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['temp_recovery_userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        $showError = true;
        $errorMessage = "Something went wrong.";
        $skip = true;
    } else {
        $row = $result->fetch_assoc();
        if ($_POST['code_input'] != $row['code']) {
            $showError = true;
            $errorMessage = "Wrong code!";
            $skip = true;
        } else {
            $tempRecoveryUsername = $_SESSION['temp_recovery_username'];
        }
        $stmt = $conn->prepare("DELETE FROM recovery_codes WHERE code = ?");
        $stmt->bind_param("s", $row['code']);
        $stmt->execute();
    }
}

if(!empty($_POST['save_password_button'])) {
    if($_POST['new_password'] != $_POST['new_password_confirm']){
        $newPasswordError = true;
    } else {
        $password_hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ? ");
        $stmt->bind_param("ss", $password_hash, $_SESSION['temp_recovery_userid'] );
        $stmt->execute();
        $stmt->close();
        $newPasswordSaved = true;
    }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Log in</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <style> <?php include "./css/login-page.css" ?> </style>
    <style> <?php include "./css/universal.css" ?> </style>
</head>
<body>
    <div class="login-dark-container" id="loginDarkContainer">
        <div class="login-recovery-container">
            <div class="login-recovery-form-container">
                <form action="index.php" method="POST" class="login-recovery-form">
                    <h1>Recover your username or password</h1>
                    <p>Please type in the email the account is registered to</p>
                    <input type="text" placeholder="Email" name="email_recovery" class="login-cred-input" required>
                    <br>
                    <div class="login-recovery-button-container">
                        <input type="submit" value="Next" name="next_recovery" class="login-button">
                        <button onclick="showDarkContainer()" class="login-button" id="login-recovery-cancel">Cancel</button>
                    </div>
                </form>
            </div>
            <div class="login-recovery-email-sent-form-container">
                <form action="index.php" method="POST" class="login-recovery-email-sent-form">
                    <h1> <?php echo "An email with a code has been sent to $mailReceiver, please type in the code. If you cannot find the email, check your spam folder."; ?> </h1>
                    <p>This code expires in 5 minutes</p>
                    <input type="text" placeholder="000000" name="code_input" class="login-cred-input" maxlength="6" minlength="6">
                    <br>
                    <div class="login-recovery-button-container">
                        <input type="submit" value="Submit" name="code_input_button" class="login-button">
                        <button class="login-button" id="login-recovery-cancel">Cancel</button>
                    </div>
                </form>
            </div>
            <div class="login-recovery-final-form-container">
                <form action="index.php" method="POST" class="login-recovery-final-form">
                    <h1>Account recovery</h1>
                    <p>The username for this account is <?php echo $tempRecoveryUsername; ?></p>
                    <div class="login-recovery-button-container" id="login-recovery-button-container-hide">
                        <button class="login-button" onclick="showNewPassword()">Recover password</button>
                        <button class="login-button" onclick="location.reload()">Done</button>
                    </div>
                    <div class="login-new-password-container" id="login-new-password-container">
                        <p>Create new password</p>
                        <p id="login-new-password-error">Passwords dont match!</p>
                        <input type="text" placeholder="New password" name="new_password" class="login-cred-input" required>
                        <br>
                        <input type="text" placeholder="Confirm new password" name="new_password_confirm" class="login-cred-input" required>
                        <div class="login-recovery-button-container">
                            <button type="submit" name="save_password_button" value="Save" class="login-button">Save</button>
                            <button class="login-button" id="login-recovery-cancel" onclick="location.reload()">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="login-new-password-saved">
                <h1>Password saved</h1>
                <button class="login-button" onclick="location.reload()">Done</button>
            </div>
        </div>
    </div>
    <a href="./phpMyAdmin/index.php" class="login-admin-link">Admin</a>
    <div class="login-container">
        <div class="login-logo-container">
            <img src="./img/Noc_Skir_Logo.svg" alt="Nocturnal Skirmish Logo" draggable="false">
        </div>
        <div class="login-form-container">
            <form action="index.php" method="POST" class="login-form">
                <div class="login-form-inner">
                <h1>Log in to GameHub</h1>
                <div class="login-error-container">
                    <?php echo $errorMessage; ?>
                </div>
                <input type="text" placeholder="Username" name="username" required class="login-cred-input" maxlength="25">
                <input type="password" placeholder="Password" name="password" required class="login-cred-input" maxlength="80">
                <p class="login-register-link">Dont have a user? <a href="create_account.php">Create account.</a></p>
                <input type="submit" value="Log in" class="login-button" name="login_button">
                <br>
                <a href="#" onclick="showDarkContainer()" class="login-forgot-link">Forgot username or password?</a>
                </div>
            </form>
        </div>
    </div>
</body>
<script type="text/javascript"><?php include "./js/script.js" ?></script>
</html>

<?php
if ($showError == true) {
    echo "<style>.login-error-container{display: block;}</style>";
};

if ($skip == false){
    if(!empty($_POST['next_recovery'])) {
        echo "<style>.login-recovery-email-sent-form-container{display: flex;}</style>";
        echo "<style>.login-recovery-form-container{display: none;}</style>";
        echo "<style>.login-dark-container{display: block;}</style>";
    }
    
    if(!empty($_POST['code_input_button'])) {
        echo "<style>.login-recovery-form-container{display: none;}</style>";
        echo "<style>.login-recovery-final-form-container{display: flex;}</style>";
        echo "<style>.login-dark-container{display: block;}</style>";
    }

    if($newPasswordSaved == true) {
        echo "<style>.login-recovery-form-container{display: none;}</style>";
        echo "<style>.login-dark-container{display: block;}</style>";
        echo "<style>.login-new-password-saved{display: flex;}</style>";
    }

    if($newPasswordError == true){
        echo "<style>.login-recovery-form-container{display: none;}</style>";
        echo "<style>.login-recovery-final-form-container{display: flex;}</style>";
        echo "<style>.login-dark-container{display: block;}</style>";
        echo "<style>#login-new-password-container{display: block;}</style>";
        echo "<style>#login-recovery-button-container-hide{display: none;}</style>";
        echo "<style>#login-new-password-error{display: block;}</style>";
    };
};
?>