<?php
// Checks if code user typed in is valid in password recovery
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";

    // Removes expired recovery codes from database
    $sql = "DELETE FROM recovery_codes WHERE expire < NOW()";
    $conn->query($sql);

    $code = htmlspecialchars($_POST['code']);

    // Check if input is empty
    if ($code === null || strlen($code) == 0){
        // Input is empty
        echo "empty";
        exit;
    }

    // Check if input is a valid code
    if (!is_numeric($code) || strlen($code) != 6) {
        // Codes is not a number or is longer or shorter than 6 digits
        echo "invalid";
        exit;
    }

    // Get recovery code and check if it exists
    $stmt = $conn->prepare("SELECT * FROM recovery_codes WHERE code = ? AND user_id = ?");
    $stmt->bind_param("ss", $code, $_SESSION['temp_recovery_userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    if (mysqli_num_rows($result) <= 0) {
        // Code doesnt exist
        echo "existexpired";
        exit;
    }
    $stmt->close();

    // Code is correct so delete it and redirect to next stage
    $stmt = $conn->prepare("DELETE FROM recovery_codes WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['temp_recovery_userid']);
    $stmt->execute();
    $stmt->close();
} else {
    header("Location: ../index.php");
}