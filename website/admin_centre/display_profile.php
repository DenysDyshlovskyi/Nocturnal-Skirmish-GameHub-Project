<?php
// Page where admins can edit user details
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // If user is unauthorized, redirect them
    require "../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['profile']);

        // Get user details from users table
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $userprofile_row = $result->fetch_assoc();

        // Get ip adresses registered to user
        $stmt = $conn->prepare("SELECT * FROM ip_adresses WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $ip_adresses = "";
        if ((mysqli_num_rows($result) <= 0)) {
            $ip_adresses = "No registered ip adresses";
        } else {
            while ($row = $result->fetch_assoc()) {
                $ip_adresses = $ip_adresses . $row['ip'] . " - " . $row['last_login'] . "<br>";
            }
        }

        // Get redeemed dev codes registered to user
        $stmt = $conn->prepare("SELECT * FROM redeemed_codes WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $dev_codes = "";
        if ((mysqli_num_rows($result) <= 0)) {
            $dev_codes = "No redeemed codes. ";
        } else {
            while ($row = $result->fetch_assoc()) {
                $dev_codes = $dev_codes . $row['code'] . "<br>";
            }
        }
    }
} else {
    header("Location: admin_login.php?error=unauth");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Admin Profile Editor: User ID <?php echo $user_id ?> </title>
    <link rel="icon" type=".image/x-icon" href="../img/favicon.png">
    <style> <?php include "../css/universal.css" ?> </style>
    <style> <?php include "./css/display-profile.css" ?> </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <div id="confirmContainer" class="confirmation-popup"></div>
    <div class="content">
        <div class="profile-container">
            <a href="#">
                <div class="banner-container" style="background-image: url(../img/profile_banners/<?php echo $userprofile_row['profile_banner'] ?>);">
                    <div class="banner-hover-pencil"></div>
                </div>
            </a>
            <div class="name-container">
                <a href="#">
                    <div class="profilepic" style="background-image: url(../img/profile_pictures/<?php echo $userprofile_row['profile_picture'] ?>);">
                        <div class="profilepic-pencil-hover"></div>
                        <img src="../img/borders/<?php echo $userprofile_row['profile_border'] ?>" alt="">
                    </div>
                </a>
                <div class="name-inner-container">
                    <h1>Username: <?php echo $userprofile_row['username'] ?></h1>
                    <p>Nickname: <?php echo $userprofile_row['nickname'] ?></p>
                    <p>User ID: <?php echo $user_id ?></p>
                    <p>Runes: <?php echo $userprofile_row['runes'] ?></p>
                    <p>Join date: <?php echo $userprofile_row['joindate'] ?></p>
                </div>
            </div>
            <textarea class="description" id="description-textarea"><?php echo $userprofile_row['description'] ?></textarea>
        </div>
        <div class="profile-button-container">
            <button onclick="saveDescription(<?php echo $userprofile_row['user_id'] ?>)">Save Description</button>
            <button>Change Username</button>
            <button>Change Nickname</button>
            <button>Change Rune Amount</button>
            <button>Change Join Date</button>
            <button>Change Border</button>
            <button style="background-color: red;">Ban User</button>
            <button style="background-color: red;">Delete User</button>
        </div>
        <div class="component-container">
            <div class="component">
                <div class="component-headline">Registered IP adresses</div>
                <div class="component-list-container">
                    <?php echo $ip_adresses ?>
                </div>
            </div>
            <div class="component">
                <div class="component-headline">Redeemed dev codes</div>
                <div class="component-list-container">
                    <?php echo $dev_codes ?>
                </div>
            </div>
            <div class="component">
                <div class="component-headline">Border inventory <button class='component-remove-button' onclick="removeAllBorders(<?php echo $userprofile_row['user_id'] ?>)">Remove all</button></div>
                <div class="component-list-container" id="border-inventory">
                    <?php
                    // Get border inventory of user and prints out p tag saying the border name with a button to remove it
                    $stmt = $conn->prepare("SELECT * FROM border_inventory WHERE user_id = ?");
                    $stmt->bind_param("s", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ((mysqli_num_rows($result) <= 0)) {
                        echo "No borders. ";
                    } else {
                        while ($row = $result->fetch_assoc()) {
                            printf("<p id='borderComponent_" . $row['border'] . "'>" . $row['border'] . "<button class='component-remove-button' onclick='removeBorder(" . $row['user_id'] . ", %s)'>Remove</button></p>", '"' . $row['border'] . '"');
                        }
                    }
                    ?>
                <br><button onclick="addNewBorder(<?php echo $user_id ?>)">Add new border to inventory</button>
                </div>
            </div>
            <div class="component">
                <div class="component-headline">Friend list</div>
                <div class="component-list-container">
                    <?php echo $dev_codes ?>
                </div>
            </div>
            <div class="component">
                <div class="component-headline">Pending friend list</div>
                <div class="component-list-container">
                    <?php echo $dev_codes ?>
                </div>
            </div>
        </div>
    </div>
    <button class="backtodash" onclick="window.location.href = 'dashboard.php'">Back to dashboard</button>
</body>
<script><?php include "./js/display_profile.js" ?></script>
</html>