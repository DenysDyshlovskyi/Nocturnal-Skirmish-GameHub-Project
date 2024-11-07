<?php
// Sets new password for user when they recover password
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";

    $password = htmlspecialchars($_POST['password']);
    $password_confirm = htmlspecialchars($_POST['password_confirm']);

    // Check if input is empty
    if ($password === null || strlen($password) == 0 || $password_confirm === null || strlen($password_confirm) == 0){
        // Input is empty
        echo "empty";
        exit;
    }

    // Check if new passwords match
    if ($password != $password_confirm) {
        echo "dontmatch";
        exit;
    }

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Update database with new password 
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $stmt->bind_param("ss", $password_hash, $_SESSION['temp_recovery_userid']);
    $stmt->execute();
    $stmt->close();
} else {
    header("Location: ../index.php");
}