<?php
require "../lib/mail.php";
require "avoid_errors.php";
//Saves new email to database
if(isset($_POST['email'])){
    if ($_POST['email'] < 0 || filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
        echo "error";
    } else {
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $_POST['email'], $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();

        $mailReceiver = $_SESSION['user_profile_email'];
        $mailSubject = "GameHub account email change";
        $mailBody = "Hello " . $_SESSION['user_profile_email'] . ". The email for your GameHub account har recently been changed to " . $_POST['email'] . ". If this is a mistake, please contact user support at: gamehub-nocskir@outlook.com";
        $mailBodyAlt = "Hello " . $_SESSION['user_profile_email'] . ". The email for your GameHub account har recently been changed to " . $_POST['email'] . ". If this is a mistake, please contact user support at: gamehub-nocskir@outlook.com";
        sendMail($mailReceiver, $mailSubject, $mailBody, $mailBodyAlt);
        $_SESSION['user_profile_email'] = $_POST['email'];

        echo $_POST['email'];
    }
} else {
    echo "error";
};
?>