<?php
// Saves description
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['user_id']);
        $description = htmlspecialchars($_POST['description']);

        $stmt = $conn->prepare("UPDATE users SET description = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $description, $user_id);
        $stmt->execute();
        $stmt->close();

        echo $description;
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}