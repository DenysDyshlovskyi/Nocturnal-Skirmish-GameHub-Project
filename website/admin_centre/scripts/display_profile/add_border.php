<?php
// Adds border to users inventory
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['user_id']);
        $border = htmlspecialchars($_POST['border']);

        // Adds border to users inventory.
        $stmt = $conn->prepare("INSERT INTO border_inventory (user_id, border) VALUES (?,?)");
        $stmt->bind_param("ss", $user_id, $border);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}