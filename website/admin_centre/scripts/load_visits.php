<?php
require "../php_scripts/avoid_errors.php";
// Loads a table for all the visits since 5.12.2024
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ./admin_login.php?error=unauth");
    exit;
} else {
    // For each row in visits table
    $stmt = $conn->prepare("SELECT * FROM visits ORDER BY date DESC LIMIT 25");
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "<p class='no-records'>No records...</p>";
    } else {
        echo "<tr>
            <td><b>Date</b></td>
            <td><b>Amount</b></td>
        </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "
                <tr>
                    <td>" . $row['date'] . "</td>
                    <td>" . $row['amount'] . "</td>
                </tr>
                ";
        }
    }
}