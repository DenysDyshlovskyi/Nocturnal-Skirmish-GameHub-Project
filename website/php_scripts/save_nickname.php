<?php
//Saves new nickname to database
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $nickname = htmlspecialchars($_POST['nickname']);
    if ($nickname < 0) {
        echo "error";
    } else {
        $stmt = $conn->prepare("UPDATE users SET nickname = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $nickname, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
        echo $nickname;
    }
} else {
    header("Location: ../index.php");
};