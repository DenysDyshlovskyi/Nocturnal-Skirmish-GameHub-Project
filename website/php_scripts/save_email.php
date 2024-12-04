<?php
//Saves new email to database
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../lib/mail.php";
    require "avoid_errors.php";
    $email = htmlspecialchars($_POST['email']);
    if ($email < 0 || filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
        echo "error";
    } else {
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $email, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();

        $mailReceiver = $_SESSION['user_profile_email'];
        $mailSubject = "GameHub account email change";
        $mailBody = "Hello " . $_SESSION['user_profile_email'] . ". The email for your GameHub account har recently been changed to " . $email . ". If this is a mistake, please contact user support at: support@nocskir.com";
        sendMail($mailReceiver, $mailSubject, $mailBody);
        $_SESSION['user_profile_email'] = $email;

        echo $email;
    }
} else {
    header("Location: ../index.php");
};