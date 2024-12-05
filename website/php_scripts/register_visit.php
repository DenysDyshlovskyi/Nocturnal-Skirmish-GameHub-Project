<?php
// Registers visit in visits table
require "avoid_errors.php";

// If the date hasnt alredy been registered
if (!isset($_SESSION['register_visit'])) {
    // Check if a row for current date already exists
    require "getdate.php";
    $stmt = $conn->prepare("SELECT * FROM visits WHERE date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        // Row for current date not created, create one
        $stmt = $conn->prepare("INSERT INTO visits (date, amount) VALUES (?, 1)");
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $stmt->close();
    } else {
        // Row found, update it
        $stmt = $conn->prepare("UPDATE visits SET amount = amount + 1 WHERE date = ?");
        $stmt->bind_param("s", $date);
        $stmt->execute();
        $stmt->close();
    }

    // Update session variable
    $_SESSION['register_visit'] = true;
}