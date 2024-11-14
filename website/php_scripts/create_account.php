<?php
// Creates account
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    require "../lib/mail.php";

    // Gets user inputs
    $username = htmlspecialchars($_POST['username']);
    $nickname = htmlspecialchars($_POST['nickname']);
    $description = htmlspecialchars($_POST['description']);
    $email = htmlspecialchars($_POST['email']);
    $email_confirm = htmlspecialchars($_POST['email_confirm']);
    $password = htmlspecialchars($_POST['password']);
    $password_confirm = htmlspecialchars($_POST['password_confirm']);
    $checkbox = htmlspecialchars($_POST['checkbox']);

    // Checks if any of the inputs, except description and checkbox, are empty
    if 
    (
        $username === null || strlen($username) == 0 ||
        $nickname === null || strlen($nickname) == 0 ||
        $email === null || strlen($email) == 0 ||
        $email_confirm === null || strlen($email_confirm) == 0 ||
        $password=== null || strlen($password) == 0 ||
        $password_confirm === null || strlen($password_confirm) == 0
    ) 
    {
        echo "empty";
        exit;
    };

    // Check if checkbox is checked
    if ($checkbox != "checked") {
        echo "unchecked";
        exit;
    }

    // Checks if emails match
    if ($email != $email_confirm) {
        echo "email_dontmatch";
        exit;
    }

    // Checks if passwords match
    if ($password != $password_confirm) {
        echo "password_dontmatch";
        exit;
    }

    // Check if its a valid email adress
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //Email is invalid
        echo "email_invalid";
        exit;
    }

    // Check if username is already taken
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        echo "username_taken";
        exit;
    }
    $stmt->close();

    // Check if email is already registered
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        echo "email_registered";
        exit;
    }
    $stmt->close();

    // Deletes expired bans
    $stmt = $conn->prepare("DELETE FROM banned WHERE duration < NOW()");
    $stmt->execute();
    $stmt->close();

    // Get ip to check if user is ip banned
    $ip = $_SERVER['REMOTE_ADDR'];

    // Checks if user trying to create account is banned.
    $stmt = $conn->prepare("SELECT * FROM banned WHERE ip = ? LIMIT 1");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        echo "banned";
        $_SESSION['user_id'] = "banned";
        exit;
    };

    // Get joindate
    require "getdate.php";
    $joindate = $date . " " . $time;

    // If description is empty, description is "no description"
    if ($description === null || strlen($description) == 0) {
        $description = "No description";
    }

    // Hash password
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    //Inserts user into database
    $stmt = $conn->prepare("INSERT INTO users (username, password, email, joindate, nickname, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $username, $password_hash, $email, $joindate, $nickname, $description);
    $stmt->execute();
    $stmt->close();

    // Get user id and set session variable and also register ip adress
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['user_id'];
    require "register_ip.php";

    // Send thank you email
    $mailReceiver = $email;
    $mailSubject = "Thank you for creating a GameHub account!";
    $mailBody = "Hey " . $username . ". Thank you for creating a GameHub account.";
    $mailBodyAlt = "Hey " . $username . ". Thank you for creating a GameHub account.";
    sendMail($mailReceiver, $mailSubject, $mailBody, $mailBodyAlt);
} else {
    header("Location: ../index.php");
}