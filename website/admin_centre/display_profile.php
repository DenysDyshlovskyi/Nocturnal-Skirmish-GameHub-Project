<?php
// Page where admins can edit user details
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // If user is unauthorized, redirect them
    require "../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: admin_login.php?error=unauth");
    } else {
        $user_id = htmlspecialchars($_POST['profile']);
        $_SESSION['displayprofile_userid'] = $user_id;

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
<body onload="ajaxGet('./scripts/display_profile/online-offline.php', 'online-offline');">
    <div id="dark-container" class="dark-container"></div>
    <div id="confirmContainer" class="confirmation-popup"></div>
    <div class="content">
        <div class="top-half-container">
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
                        <h1 id="username-h1">Username: <?php echo $userprofile_row['username'] ?></h1>
                        <p id="nickname-p">Nickname: <?php echo $userprofile_row['nickname'] ?></p>
                        <p>User ID: <?php echo $user_id ?></p>
                        <p id="online-offline"></p>
                    </div>
                    <div class="name-inner-container-divider"></div>
                    <div class="name-inner-container">
                        <h1>Other info:</h1>
                        <p id="runes-p">Runes: <?php echo $userprofile_row['runes'] ?></p>
                        <p id="joindate-p">Join date: <?php echo $userprofile_row['joindate'] ?></p>
                        <p id="email-p">E-mail: <?php echo $userprofile_row['email'] ?></p>
                    </div>
                </div>
                <textarea class="description" id="description-textarea"><?php echo $userprofile_row['description'] ?></textarea>
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
                        $stmt->close();
                        ?>
                    <br><button onclick="ajaxGet('./spa/add_border.php', 'dark-container')">Add new border to inventory</button>
                    </div>
                </div>
                <div class="component">
                    <div class="component-headline">Friend list <button class='component-remove-button' onclick="removeAllFriends(<?php echo $userprofile_row['user_id'] ?>)">Remove all</button></div>
                    <div class="component-list-container" id="friend_list">
                    <?php
                        // Gets friend list of user
                        $stmt = $conn->prepare("SELECT * FROM friend_list WHERE user_id_1 = ?");
                        $stmt->bind_param("s", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ((mysqli_num_rows($result) <= 0)) {
                            echo "No friends. ";
                        } else {
                            while ($row = $result->fetch_assoc()) {
                                $stmt2 = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
                                $stmt2->bind_param("s", $row['user_id_2']);
                                $stmt2->execute();
                                $result2 = $stmt2->get_result();
                                $friend_row = $result2->fetch_assoc();
                                echo "<p id='friendListComponent_" . $row['user_id_2'] . "'>" . $friend_row['username'] . " (uID: " . $row['user_id_2'] . ")<button class='component-remove-button' onclick='removeFriend(" . $row['user_id_1'] . ", " . $row['user_id_2'] . ")'>Remove</button></p>";
                            }
                        }
                        $stmt->close();
                        ?>
                    </div>
                </div>
                <div class="component">
                    <div class="component-headline">Pending friend list <button class='component-remove-button' onclick="removeAllPendingFriends(<?php echo $userprofile_row['user_id'] ?>)">Remove all</button></div>
                    <div class="component-list-container" id="pending_friend_list">
                    <?php
                        // Gets outgoing pending friend list of user
                        $stmt = $conn->prepare("SELECT * FROM pending_friend_list WHERE user_id_1 = ?");
                        $stmt->bind_param("s", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ((mysqli_num_rows($result) <= 0)) {
                            echo "No outgoing pending friends. <br>";
                        } else {
                            while ($row = $result->fetch_assoc()) {
                                $stmt2 = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
                                $stmt2->bind_param("s", $row['user_id_2']);
                                $stmt2->execute();
                                $result2 = $stmt2->get_result();
                                $friend_row = $result2->fetch_assoc();
                                echo "<p id='pendingFriendListComponent_" . $row['id'] . "'>Outgoing: (Sent: " . $row['sent'] . ") " . $friend_row['username'] . " (uID: " . $row['user_id_2'] . ")<button class='component-remove-button' onclick='removePendingOutgoingFriend(" . $row['user_id_1'] . ", " . $row['user_id_2'] . ", " . $row['id'] . ")'>Remove</button></p>";
                            }
                        }
                        $stmt->close();

                        // Gets incoming pending friend list of user
                        $stmt = $conn->prepare("SELECT * FROM pending_friend_list WHERE user_id_2 = ?");
                        $stmt->bind_param("s", $user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ((mysqli_num_rows($result) <= 0)) {
                            echo "No incoming pending friends. ";
                        } else {
                            while ($row = $result->fetch_assoc()) {
                                $stmt2 = $conn->prepare("SELECT username FROM users WHERE user_id = ?");
                                $stmt2->bind_param("s", $row['user_id_1']);
                                $stmt2->execute();
                                $result2 = $stmt2->get_result();
                                $friend_row = $result2->fetch_assoc();
                                echo "<p id='pendingFriendListComponent_" . $row['id'] . "'>Incoming: (Sent: " . $row['sent'] . ") " . $friend_row['username'] . " (uID: " . $row['user_id_1'] . ")<button class='component-remove-button' onclick='removePendingIncomingFriend(" . $row['user_id_1'] . ", " . $row['user_id_2'] . ", " . $row['id'] . ")'>Remove</button></p>";
                            }
                        }
                        $stmt->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="profile-button-container">
            <button onclick="saveDescription(<?php echo $userprofile_row['user_id'] ?>)">Save Description</button>
            <button onclick="ajaxGet('./spa/change_username.php', 'dark-container');">Change Username</button>
            <button onclick="ajaxGet('./spa/change_nickname.php', 'dark-container');">Change Nickname</button>
            <button onclick="ajaxGet('./spa/change_runes.php', 'dark-container');">Change Rune Amount</button>
            <button onclick="ajaxGet('./spa/change_joindate.php', 'dark-container');">Change Join Date</button>
            <button onclick="ajaxGet('./spa/change_email.php', 'dark-container');">Change E-mail</button>
            <button>Change Border</button>
            <button onclick="ajaxGet('./spa/change_password.php', 'dark-container');">Change Password</button>
            <br>
            <br>
            <button style="background-color: red;">Ban User</button>
            <form action="./scripts/delete_user.php" method="POST">
                <button style="background-color: red;">Delete User</button>
                <input type="hidden" value="<?php echo $user_id ?>" name="user_id">
            </form>
        </div>
        <button class="backtodash" onclick="window.location.href = 'dashboard.php'">Back to dashboard</button>
    </div>
</body>
<script>
    // Starts 5 second interval to update users online offline status
    setInterval(function(){
        ajaxGet('./scripts/display_profile/online-offline.php', 'online-offline');
    }, 5000);
</script>
<script><?php include "./js/display_profile.js" ?></script>
</html>