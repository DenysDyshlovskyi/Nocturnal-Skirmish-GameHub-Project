<?php
// Removes redeemed code specified when remove button is pressed
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $row_id = htmlspecialchars($_POST['row_id']);

        // Gets redeemed code
        $stmt = $conn->prepare("SELECT code FROM redeemed_codes WHERE id = ?");
        $stmt->bind_param("s", $row_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        // Passes code to JavaScript
        echo $row['code'];
        $stmt->close();

        // Removes redeemed code
        $stmt = $conn->prepare("DELETE FROM redeemed_codes WHERE id = ?");
        $stmt->bind_param("s", $row_id);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}