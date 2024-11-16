<?php
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Checks if a message exists
    require "avoid_errors.php";
    $message_id = htmlspecialchars($_POST['messageID']);
    $conn -> select_db("gamehub_messages");
    $tablename = $_SESSION['current_table'];

    $stmt = $conn->prepare("SELECT message_id FROM $tablename WHERE message_id = ?");
    $stmt->bind_param("s", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ((mysqli_num_rows($result) <= 0)) {
        echo "doesntexist";
    };

    $conn -> select_db("gamehub");
    $stmt->close();
} else {
    header("Location: ../../index.php");
}