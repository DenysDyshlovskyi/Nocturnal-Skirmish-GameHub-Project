<?php
require "avoid_errors.php";
$_POST['devcode'] = "DEVCODE_ALLBORDERS";
if(isset($_POST['devcode'])){
    // Checks if code has been redeemed in the past
    $stmt = $conn->prepare("SELECT * FROM redeemed_codes WHERE user_id = ? AND code = ?");
    $stmt->bind_param("ss", $_SESSION['user_id'], $_POST['devcode']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ((mysqli_num_rows($result) > 0)) {
        $stmt->close();
        echo "alreadyredeemed";
        exit;
    };

    // Check if code exists
    $stmt = $conn->prepare("SELECT * FROM dev_codes WHERE code = ?");
    $stmt->bind_param("s", $_POST['devcode']);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) <= 0) {
        $stmt->close();
        echo "codenotfound";
        exit;
    } else {
        // Checks if code is a special code.
        require dirname(dirname(__FILE__)) . "/config/devcode_special.php";
        specialDevCode($_POST['devcode']);

        // Gives user rewards
        $row = $result->fetch_assoc();
        if ($row['runes'] != NULL) {
            $sql = "UPDATE users SET runes = runes + " . $row['runes'] . " WHERE user_id = " . $_SESSION['user_id'];
            $conn->query($sql);
        }
        if ($row['border'] != NULL) {
            
        }
        if ($row['skin'] != NULL) {
            
        }

        // User cant redeem code in future
        $stmt->close();
        $sql = "INSERT INTO redeemed_codes (user_id, code) VALUES (" . $_SESSION['user_id'] . ",'" . $_POST['devcode'] . "')";
        $conn->query($sql);
    }
};
?>