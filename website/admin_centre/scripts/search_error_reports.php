<?php
// Searches through error reports table
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../admin_login.php?error=empty");
        exit;
    } else {
        $searchQuery = htmlspecialchars($_POST['search']);

        // Search query with % wildcard, orders by most recent
        $stmt = $conn->prepare("SELECT * FROM error_reports WHERE user_id = ? OR lower(category) LIKE CONCAT('%', ?, '%') OR lower(timestamp) LIKE CONCAT('%', ?, '%') OR lower(ip) LIKE CONCAT('%', ?, '%') ORDER BY unix_timestamp DESC LIMIT 10");
        $stmt->bind_param("ssss", $searchQuery, $searchQuery, $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        if ((mysqli_num_rows($result) <= 0)) {
            echo "none";
        } else {
            // Echo categories
            echo "          <tr>
                                <td><b>IP</b></td>
                                <td><b>User ID</b></td>
                                <td><b>Sent</b></td>
                                <td><b>Category</b></td>
                                <td><b>Report</b></td>
                            </tr>";
            while ($row = $result->fetch_assoc()) {
                // Results found, print out the rows
                echo "<tr>
                        <td>" . $row['ip'] . "</td>
                        <td>" . $row['user_id'] . "</td>
                        <td>" . $row['timestamp'] . "</td>
                        <td>" . $row['category'] . "</td>
                        <td><button onclick='viewErrorReport(" . $row['id'] . ")'>See Report</button></td>";
            }
        }
        $stmt->close();
    }
} else {
    header("Location: ../admin_login.php?error=unauth");
    exit;
}