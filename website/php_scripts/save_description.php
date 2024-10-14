<?php
require "avoid_errors.php";
// Saves description to database
if(isset($_POST['description'])){
    $stmt = $conn->prepare("UPDATE users SET description = ? WHERE user_id = ?");
    $stmt->bind_param("ss", $_POST['description'], $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
};
?>