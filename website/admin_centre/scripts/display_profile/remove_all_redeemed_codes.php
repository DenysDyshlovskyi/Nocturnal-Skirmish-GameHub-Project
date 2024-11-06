<?php
// Removes all redeemed codes when remove all button is pressed
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['user_id']);

        // Removes redeemed codes
        $stmt = $conn->prepare("DELETE FROM redeemed_codes WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}