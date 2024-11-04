<?php
// Saves email
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['user_id']);
        $new_email = htmlspecialchars($_POST['new_email']);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $new_email);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            echo "taken";
            $stmt->close();
            exit;
        } else {
            $stmt = $conn->prepare("UPDATE users SET email = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $new_email, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}