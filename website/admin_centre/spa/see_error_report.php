<?php
session_start();
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
} else {
    require "../../php_scripts/avoid_errors.php";
    // Gets information about error report
    $stmt = $conn->prepare("SELECT * FROM error_reports WHERE id = ?");
    $stmt->bind_param("s", $_SESSION['current_admin_error_report']);
    $stmt->execute();
    $result = $stmt->get_result();
    $errorreport_row = $result->fetch_assoc();

    // Get information about user who sent the report
    $stmt2 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt2->bind_param("s", $errorreport_row['user_id']);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $user_row = $result2->fetch_assoc();
}
?>
<style><?php include "./css/see-error-report.css" ?></style>
<div class="error-report-container">
    <h1 class="error-report-headline">Error report</h1>
    <div class="error-report-details">
        <p>
            Sent by: <div class="username-container">
                        <div class="error-report-profilepic" style="background-image: url(../img/profile_pictures/<?php echo $user_row['profile_picture'] ?>);">
                            <img src="../img/borders/<?php echo $user_row['profile_border'] ?>">
                        </div>
                        <?php echo $user_row['username'] ?>
                    </div>
        </p>
        <p>IP adress: <?php echo $errorreport_row['ip'] ?></p>
        <p>Sent: <?php echo $errorreport_row['timestamp'] ?></p>
        <p>Category: <?php echo $errorreport_row['category'] ?></p>
    </div>
    <div class="error-report-article">
        <h1>What happened?</h1>
        <p><?php echo $errorreport_row['what_happened'] ?></p>
        <?php
        if ($errorreport_row['screenshot'] == null) {
            echo "<h1>Screenshot: no screenshot provided</h1>";
        } else {
            echo "<h1>Screenshot:</h1>
                    <img src='../img/error_screenshots/" . $errorreport_row['screenshot'] . "'>";
        }
        ?>
    </div>
    <button onclick="removeDarkContainer()">Close</button>
</div>

<?php
$stmt->close();
$stmt2->close();
?>