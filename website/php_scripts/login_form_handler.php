<?php
// Handles login form
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";

    // Gets username and password
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Check if inputs are empty
    if ($username === null || strlen($username) == 0 || $password === null || strlen($password) == 0){
        echo "empty";
        exit;
    };

    // Check if user exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        // User doesnt exist
        echo "exist";
        exit;
    } else {
        $row = $result->fetch_assoc();
    }

    // Check if password is correct
    if (!password_verify($password, $row['password'])){
        echo "wrong";
        exit;
    };

    // Register ip adress
    $_SESSION['user_id'] = $row['user_id'];
    $stmt->close();
    require "register_ip.php";

    // Check if user is banned, first delete expired bans in case user WAS banned
    $stmt = $conn->prepare("DELETE FROM banned WHERE duration < NOW()");
    $stmt->execute();
    $stmt->close();

    // Checks if user trying to log in is banned.
    $stmt = $conn->prepare("SELECT * FROM banned WHERE user_id = ? OR ip = ? LIMIT 1");
    $stmt->bind_param("ss", $_SESSION['user_id'], $ip);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        // Row was found, means user is banned
        $row = $result->fetch_assoc();
        if ($row['type'] == "perm") {
            echo "You have been permanently banned! Reason: " . $row['reason'];
        } else {
            echo "You have been temporaily banned! Expires: " . $row['duration'] . " - Reason: " . $row['reason'];
        }
        $_SESSION['user_id'] = "banned";
    } else {
        echo "correct";
    }
} else {
    header("Location: ../index.php");
}