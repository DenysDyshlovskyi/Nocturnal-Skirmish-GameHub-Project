<?php
session_start();

require "./config/conn.php";
require "./lib/mail.php";

$showError = false;
$errorMessage = "";

if(isset($_POST['next_button'])){
    //Check if confirm inputs match with actual inputs.
    if($_POST['email'] != $_POST['email_confirm']){
        $showError = true;
        $errorMessage = "Emails dont match!";
        goto end;
    }
    if($_POST['password'] != $_POST['password_confirm']){
        $showError = true;
        $errorMessage = "Passwords dont match!";
        goto end;
    }

    //Prevents XSS
    $username = htmlspecialchars($_POST['username']);
    $nickname = htmlspecialchars($_POST['nickname']);
    $description = htmlspecialchars($_POST['description']);
    $email = htmlspecialchars($_POST['email']);

    //Checks if username is already taken
    // The ? below are parameter markers used for variable binding
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $showError = true;
        $errorMessage = "Username taken!";
        goto end;
    }
    $stmt->close();

    //Checks if email is already registered
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $showError = true;
        $errorMessage = "Email already registered!";
        goto end;
    }
    $stmt->close();

    require "./php_scripts/getdate.php";

    $joindate = $date . " " . $time;

    if(!isset($description) || trim($description) == ''){
        $description = "No description";
    };

    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //Inserts user into database
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, joindate, nickname, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $password_hash, $email, $joindate, $nickname, $description);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['user_id'];
    require "./php_scripts/register_ip.php";

    $mailReceiver = $_POST['email'];
    $mailSubject = "Thank you for creating a GameHub account!";
    $mailBody = "Hey " . $username . ". Thank you for creating a GameHub account.";
    $mailBodyAlt = "Hey " . $username . ". Thank you for creating a GameHub account.";
    sendMail($mailReceiver, $mailSubject, $mailBody, $mailBodyAlt);

    header("Location: hub.php");
};

end:
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Create account</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
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
                    <input type="text" placeholder="Username" name="username" required class="ca-creation-cred-input" maxlength="25">
                    <br>
                    <input type="text" placeholder="Nickname*" name="nickname" required class="ca-creation-cred-input" maxlength="25">
                    <br>
                    <textarea name="description" placeholder="Description" class="ca-creation-description-input" maxlength="500"></textarea>
                </div>
                <div class="ca-creation-form-additional-information-container">
                    <h1 class="ca-form-headline">Additional information</h1>
                    <input type="email" placeholder="E-mail" name="email" required class="ca-creation-cred-input" maxlength="128">
                    <br>
                    <input type="email" placeholder="Confirm e-mail" name="email_confirm" required class="ca-creation-cred-input" maxlength="128">
                    <br>
                    <input type="password" placeholder="Password" name="password" required class="ca-creation-cred-input" maxlength="80">
                    <br>
                    <input type="password" placeholder="Confirm password" name="password_confirm" required class="ca-creation-cred-input" maxlength="80">
                    <br>
                    <input type="checkbox" required>I agree to GameHub's <a href="" class="ca-terms-link">terms and conditions.</a>
                    <p class="ca-username-indefinite-warning">*You can always change your nickname, but the username is indefinite</p>
                    <div class="ca-creation-error-container">
                        <?php echo $errorMessage; ?>
                    </div>
                    <span id="ca-login-button-span"><button class="ca-next-button" onclick="location.href = 'index.php'">Back to login</button></span>
                    <span><input type="submit" value="Create account" class="ca-next-button" name="next_button"></span>
                </div>
            </form>
        </div>
    </div>
</body>
<script type="text/javascript"><?php include "./js/script.js" ?></script>
</html>

<?php
if ($showError == true) {
    echo "<style>.ca-creation-error-container{display: block;}</style>";
};
?>