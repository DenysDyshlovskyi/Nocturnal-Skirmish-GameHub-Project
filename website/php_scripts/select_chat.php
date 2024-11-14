<?php
// Selects a chat to display messages from
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $tablename = htmlspecialchars($_POST['tablename']);

    // Checks if logged in user has access to the table
    $stmt = $conn->prepare("SELECT * FROM chats WHERE user_id = ? AND tablename = '$tablename'");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "error";
        exit;
    }
    $stmt->close();

    // Set the current table to the tablename
    $_SESSION['current_table'] = $tablename;
} else {
    header("Location: ../index.php");
}