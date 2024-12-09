<?php
// Registers ip adress in database
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";

    require "get_user_ip.php";

    // Check if ip has already been registered
    $stmt = $conn->prepare("SELECT * FROM ip_adresses WHERE user_id = ? AND ip = ?");
    $stmt->bind_param("ss", $_SESSION['user_id'], $ip);
    $stmt->execute();
    $result = $stmt->get_result();

    // Get current date and time
    require "getdate.php";
    $last_login = $date . " " . $time;

    if($result->num_rows === 0){
        // If it hasnt been registered
        $stmt = $conn->prepare("INSERT INTO ip_adresses (user_id, ip, last_login) VALUES (?,?,?)");
        $stmt->bind_param("sss", $_SESSION['user_id'], $ip, $last_login);
        $stmt->execute();
        $stmt->close();
    } else {
        //If it has been registered
        $stmt = $conn->prepare("UPDATE ip_adresses SET last_login = ? WHERE user_id = ? AND ip = ?");
        $stmt->bind_param("sss", $last_login, $_SESSION['user_id'], $ip);
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../index.php");
}