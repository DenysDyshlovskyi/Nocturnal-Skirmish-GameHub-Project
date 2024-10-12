<?php
session_start();
require "./php_scripts/conn.php";
require "./php_scripts/get_loggedin_image_border.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub</title>
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/hub-page.css" ?> </style>
</head>
<body id="hub-body">
    <div class="hub-corner-profile-container">
        <div class="hub-corner-profilepic-container" id="hub-corner-profilepic-container">
            <a href="user_settings.php">
                <img src="<?php echo $_SESSION['user_profile_border']; ?>" alt="Profile Border" class="hub-corner-profilepic-border">
                <div class="hub-corner-profilepic" style="background-image: url(<?php echo $_SESSION['user_profile_picture']; ?>);"></div>
            </a>
        </div>
        <div class="hub-corner-profile-dropdown">
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-settings" title="Settings" onclick="window.location.href = 'user_settings.php';"></button>
            <div class="hub-corner-profile-dropdown-divider"></div>
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-friends" title="Friends"></button>
            <div class="hub-corner-profile-dropdown-divider"></div>
            <button class="hub-corner-profile-dropdown-button" id="dropdown-button-chats" title="Chats"></button>
        </div>
    </div>
    
</body>
</html>