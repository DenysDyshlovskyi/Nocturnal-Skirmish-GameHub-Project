<?php
require "../lib/mail.php";
require "avoid_errors.php";
//Saves new password to database
if(isset($_POST['password']) && isset($_POST['confirmpassword'])){
    //Checks if the confirm password matches with the original one
    if ($_POST['password'] != $_POST['confirmpassword']) {
        echo "dontmatch";
    } else if ($_POST['password'] < 0) {
        echo "empty";
    } else {
        $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $password_hash, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    }
} else {
    echo "error";
};
?>