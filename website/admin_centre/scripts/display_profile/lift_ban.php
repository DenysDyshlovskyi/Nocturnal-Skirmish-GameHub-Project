<?php
// Lifts ban
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $row_id = htmlspecialchars($_POST['row_id']);

        $stmt = $conn->prepare("DELETE FROM banned WHERE id = ?");
        $stmt->bind_param("s", $row_id);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}