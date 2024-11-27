<?php
// Saves border to database
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $bordername = htmlspecialchars($_POST['bordername']);
    if (file_exists("../img/borders/" . $bordername)) {
        // Check if border is in users inventory
        $stmt = $conn->prepare("SELECT * FROM border_inventory WHERE user_id = ? AND border = ?");
        $stmt->bind_param("ss", $_SESSION['user_id'], $bordername);
        $stmt->execute();
        $result = $stmt->get_result();
        if ((mysqli_num_rows($result) <= 0)) {
            // If the user doesnt have the border in their inventory
            echo "error";
            $stmt->close();
            exit;
        }
        $stmt->close();

        // Set the border
        $stmt = $conn->prepare("UPDATE users SET profile_border = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $bordername, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "error";
    }
} else {
    header("Location: ../index.php");
}