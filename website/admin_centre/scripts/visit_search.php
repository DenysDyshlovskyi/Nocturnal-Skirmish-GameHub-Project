<?php
// Searches for visits
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../admin_login.php?error=unauth");
    } else {
        $searchQuery = htmlspecialchars($_POST['search']);
        $stmt = $conn->prepare("SELECT * FROM visits WHERE date LIKE CONCAT('%', ?, '%') OR amount LIKE CONCAT('%', ?, '%')LIMIT 15");
        $stmt->bind_param("ss", $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();
        if ((mysqli_num_rows($result) <= 0)) {
            echo "none";
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
        $stmt->close();
    }
} else {
    header("Location: ../../index.php");
}