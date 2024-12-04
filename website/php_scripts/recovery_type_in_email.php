<?php
//Sends email to user when they have typed in their email adress in password recovery
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    require "../lib/mail.php";

    $email = htmlspecialchars($_POST['email']);

    // Check if input is empty
    if ($email === null || strlen($email) == 0){
        // Input is empty
        echo "empty";
        exit;
    }

    // Check if its a valid email adress
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //Email is invalid
        echo "invalid";
        exit;
    }
    
    // Check if email is registered
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows <= 0){
        // No rows found, email doesnt exist in database
        echo "notregistered";
        exit;
    }
    $row = $result->fetch_assoc();

    // Defines session variables for later
    $_SESSION['temp_recovery_userid'] = $row['user_id'];
    $_SESSION['temp_recovery_username'] = $row['username'];
    $_SESSION['temp_recovery_email'] = $email;

    $stmt->close();

    // Generate random 6 digit code
    $randomCode = random_int(100000, 999999);

    // Removes all previous recovery codes for the user
    $stmt = $conn->prepare("DELETE FROM recovery_codes WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['temp_recovery_userid']);
    $stmt->execute();

    // Put recovery code in database
    $stmt = $conn->prepare("INSERT INTO recovery_codes (user_id, code, expire) VALUES (?, ?, NOW() + INTERVAL 5 MINUTE)");
    $stmt->bind_param("ss", $_SESSION['temp_recovery_userid'], $randomCode);
    $stmt->execute();

    // Sends email with code
    $mailReceiver = $email;
    $mailSubject = "GameHub account recovery";
    $mailBody = "Hey " . $email . ". The recovery code is: $randomCode";
    sendMail($mailReceiver, $mailSubject, $mailBody);
} else {
    header("Location: ../index.php");
}