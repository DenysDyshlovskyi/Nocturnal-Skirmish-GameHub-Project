<?php
session_start();

// Deletes user specified
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../admin_login.php?error=unauth");
        exit;
    } else {
        require "../../config/conn.php";
        $user_id = htmlspecialchars($_POST['user_id']);
        // First, delete user from users table
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        //Second, delete user from all other tables, like for example border_inventory, friend list excetera

        //border_inventory
        $stmt = $conn->prepare("DELETE FROM border_inventory WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        //friend list - user_id_1
        $stmt = $conn->prepare("DELETE FROM friend_list WHERE user_id_1 = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        //friend list - user_id_2
        $stmt = $conn->prepare("DELETE FROM friend_list WHERE user_id_2 = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        //ip_adresses
        $stmt = $conn->prepare("DELETE FROM ip_adresses WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        //pending_friend_list - user_id_1
        $stmt = $conn->prepare("DELETE FROM pending_friend_list WHERE user_id_1 = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        //pending_friend_list - user_id_1
        $stmt = $conn->prepare("DELETE FROM pending_friend_list WHERE user_id_2 = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        //recovery_codes
        $stmt = $conn->prepare("DELETE FROM recovery_codes WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        //redeemed_codes
        $stmt = $conn->prepare("DELETE FROM redeemed_codes WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        // banned
        $stmt = $conn->prepare("DELETE FROM banned WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        // kick
        $stmt = $conn->prepare("DELETE FROM kick WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        // Archive every two user chat they were in
        $stmt = $conn->prepare("SELECT * FROM chats WHERE user_id = ? AND type = 'two_user'");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();

        //Redirect to dashboard
        header("Location: ../dashboard.php?userdeleted=$user_id");
        exit;
    }
} else {
    header("Location: ../admin_login.php?error=unauth");
    exit;
}