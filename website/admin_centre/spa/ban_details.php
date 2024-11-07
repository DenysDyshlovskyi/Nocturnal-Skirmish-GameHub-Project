<?php
session_start();
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
};
?>
<style>
    #dark-container {
        display: block;
    }
</style>
<style><?php include "./css/ban-details.css" ?></style>
<div class="ban-details-container">
    <h1>Ban details for uID: <?php echo $_SESSION['displayprofile_userid'] ?></h1>
    <br>
    <?php
    // Loads in bans for user

    // Check if user is ip banned
    require "../../php_scripts/avoid_errors.php";
    $stmt = $conn->prepare("SELECT * FROM ip_adresses WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['displayprofile_userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "User is not ip banned. <br><br>";
    } else {
        $notIpBanned = 1;
        while ($row = $result->fetch_assoc()) {
            $stmt1 = $conn->prepare("SELECT * FROM banned WHERE ip = ?");
            $stmt1->bind_param("s", $row['ip']);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $row1 = $result1->fetch_assoc();
            if ((mysqli_num_rows($result1) > 0)) {
                echo "<b>IP adress " . $row1['ip'] . " is banned and is associated with user. <button onclick='liftBan(" . $row1['id'] . ")'>Lift ban</button> </b><br>";
                echo "<b>Type: </b>" . $row1['type'] . "<br>";
                if (!is_null($row1['duration'])) {
                    echo "<b>Duration: </b>" . $row1['duration'] . "<br>";
                }
                echo "<b>Reason: </b><br><div>" .$row1['reason'] . "</div><br><br>";
                $notIpBanned = 0;
            }
        }
        if ($notIpBanned == 1) {
            echo "User is not ip banned. <br><br>";
        }
    }

    // Checks if user id is banned
    $stmt = $conn->prepare("SELECT * FROM banned WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['displayprofile_userid']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "User is not uID banned. <br><br>";
    } else {
        $row = $result->fetch_assoc();
        echo "<b>User ID " . $row['user_id'] . " is banned. <button onclick='liftBan(" . $row['id'] . ")'>Lift ban</button> </b><br>";
        echo "<b>Type: </b>" . $row['type'] . "<br>";
        if ($row['duration'] != NULL) {
            echo "<b>Duration: </b>" . $row['duration'] . "<br>";
        }
        echo "<b>Reason: </b><br><div>" .$row['reason'] . "</div><br><br>";
    }
    ?>
    <br>
    <button onclick="removeDarkContainer()">Close</button>
</div>