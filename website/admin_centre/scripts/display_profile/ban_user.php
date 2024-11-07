<?php
// Bans user
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
        exit;
    } else {
        if (isset($_POST['temporary'])) {
            $type = "temp";
            $duration = htmlspecialchars($_POST['duration']);
        } else if (isset($_POST['permanent'])) {
            $type = "perm";
            $duration = NULL;
        }

        $reason = htmlspecialchars($_POST['reason']);
        $user_id = $_SESSION['displayprofile_userid'];

        // Put ban in ban table for each ip adress attached to user
        $stmt = $conn->prepare("SELECT * FROM ip_adresses WHERE user_id = ?");
        $stmt->bind_param("s", $_SESSION['displayprofile_userid']);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO banned (ip, type, duration, reason) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $row['ip'], $type, $duration, $reason);
            $stmt->execute();
        };

        //Put ban in ban table for user id
        $stmt = $conn->prepare("INSERT INTO banned (user_id, type, duration, reason) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $user_id, $type, $duration, $reason);
        $stmt->execute();
        $stmt->close();

        //Put user_id in kick table incase theyre online
        $stmt = $conn->prepare("INSERT INTO kick (user_id) VALUES (?)");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $stmt->close();

        // Redirect
        header("Location: ../../dashboard.php?userbanned=$user_id");
    };
} else {
    header("Location: ../../login_admin.php?error=unauth");
}