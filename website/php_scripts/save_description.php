<?php
// Saves description to database
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $description = htmlspecialchars($_POST['description']);
    $stmt = $conn->prepare("UPDATE users SET description = ? WHERE user_id = ?");
    $stmt->bind_param("ss", $description, $_SESSION['user_id']);
    $stmt->execute();
    $stmt->close();
} else {
    header("Location: ../index.php");
}