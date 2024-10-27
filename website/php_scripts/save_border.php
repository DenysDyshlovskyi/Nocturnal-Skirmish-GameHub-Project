<?php
require "avoid_errors.php";
// Saves border to database
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bordername = htmlspecialchars($_POST['bordername']);
    if (file_exists("../img/borders/" . $bordername)) {
        $stmt = $conn->prepare("UPDATE users SET profile_border = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $bordername, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "error";
    }
} else {
    header("Location: ../index.php");
}