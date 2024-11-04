<?php
require dirname(dirname(dirname(__DIR__))) . "/php_scripts/avoid_errors.php";
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../../admin_login.php?error=unauth");
} else {
    $hasAllBorders = 0;

    // For each border in borders directory, add it to table
    $user_id = $_SESSION['displayprofile_userid'];
    $borders = array_diff(scandir(dirname(dirname(dirname(__DIR__))) . "/img/borders"), array('..', '.'));
    foreach($borders as $file) {
        // Check if user already has border in inventory
        $stmt = $conn->prepare("SELECT * FROM border_inventory WHERE user_id = ? AND border = ?");
        $stmt->bind_param("ss", $user_id, $file);
        $stmt->execute();
        $result = $stmt->get_result();
        if ((mysqli_num_rows($result) <= 0)) {
            $hasAllBorders = 1;
            printf("
            <tr>
                <td>
                    <img src='../img/borders/$file'>
                </td>
                <td>
                    $file
                </td>
                <td>
                    <button onclick='addBorder($user_id, %s)'>Add to inventory</button>
                </td>
            </tr>
            ", '"' . $file . '"');
        };
    };

    // If the user already has all the borders, say so
    if ($hasAllBorders == 0) {
        echo "<tr><td colspan='3'> User has all borders. </td></tr>";
    }
}
