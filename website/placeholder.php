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
            require "./php_scripts/register_ip.php";
            // Deletes expired bans
            $stmt1 = $conn->prepare("DELETE FROM banned WHERE duration < NOW()");
            $stmt1->execute();
            $stmt1->close();

            // Checks if user trying to log in is banned.
            $stmt1 = $conn->prepare("SELECT * FROM banned WHERE user_id = ? OR ip = ? LIMIT 1");
            $stmt1->bind_param("ss", $_SESSION['user_id'], $ip);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            if($result1->num_rows > 0){
                $row1 = $result1->fetch_assoc();
                if ($row1['type'] == "perm") {
                    printf("<script>alert('You have been banned permanently! Reason: %s')</script>", '"' . $row1['reason'] . '"');
                } else {
                    printf("<script>alert('You have been banned! Expires: %s. Reason: %s')</script>", '"' . $row1['duration'] . '"', '"' . $row1['reason'] . '"');
                }
                $showError = true;
                $errorMessage = "You have been banned!";

                $_SESSION['user_id'] = "banned";
            } else {
                header("Location: ./hub.php");
            }
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