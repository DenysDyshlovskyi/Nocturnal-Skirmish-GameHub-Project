<?php
// Checks if user should be kicked
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";

    $stmt = $conn->prepare("SELECT * FROM kick WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if (mysqli_num_rows($result) > 0) {
        echo "kick";
        $stmt = $conn->prepare("DELETE FROM kick WHERE user_id = ?");
        $stmt->bind_param("s", $_SESSION['user_id']);
        $stmt->execute();
        session_destroy();
    }
    $stmt->close();
} else {
    header("Location: ../index.php");
}