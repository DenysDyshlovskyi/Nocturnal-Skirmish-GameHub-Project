<?php
// Really make sure the user has access to the groupchat
require "../../php_scripts/avoid_errors.php";

// Check if the current chat is a groupchat and if the user has access
if (isset($_SESSION['current_table'])) {
    // Does the user have access to the chat?
    $stmt = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND user_id = ?");
    $stmt->bind_param("ss", $groupchat, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        echo "Error <button onclick='removeDarkContainer()'>Close</button>";
        exit;
    }
    $stmt->close();

    // Is the chat a groupchat?
    $stmt = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND type = 'groupchat'");
    $stmt->bind_param("s", $groupchat);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        echo "Error <button onclick='removeDarkContainer()'>Close</button>";
        exit;
    }
    $stmt->close();
} else {
    echo "Error <button onclick='removeDarkContainer()'>Close</button>";
    exit;
}
?>
<style><?php include "./css/groupchat-settings.css" ?></style>
<div class="groupchat-settings-container">

</div>