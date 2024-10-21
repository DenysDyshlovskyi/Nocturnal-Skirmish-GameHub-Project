<?php
require "avoid_errors.php";
// Saves border to database
if(isset($_POST['bordername'])){
    if (file_exists("../img/borders/" . $_POST['bordername'])) {
        $stmt = $conn->prepare("UPDATE users SET profile_border = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $_POST['bordername'], $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "error";
    }
};
?>