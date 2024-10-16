<?php
require "avoid_errors.php";
//Saves new nickname to database
if(isset($_POST['nickname'])){
    $stmt = $conn->prepare("UPDATE users SET nickname = ? WHERE user_id = ?");
    $stmt->bind_param("ss", $_POST['nickname'], $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
    echo $_POST['nickname'];
};
?>