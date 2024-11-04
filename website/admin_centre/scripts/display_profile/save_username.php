<?php
// Saves username
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['user_id']);
        $new_username = htmlspecialchars($_POST['new_username']);

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $new_username);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            echo "taken";
            $stmt->close();
            exit;
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $new_username, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    }
} else {
    header("Location: ../../admin_login.php?error=unauth");
    exit;
}