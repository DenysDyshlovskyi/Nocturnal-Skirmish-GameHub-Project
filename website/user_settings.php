<?php
//WIP
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - User settings</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/user-settings-page.css" ?> </style>
</head>
<body id="settings-body">
    <div class="settings-container">
        <button class="settings-backtohub" title="Back to Hub" onclick="window.location.href = 'hub.php';">Back to Hub</button>
        <div class="settings-sidebar">
            <p class="settings-sidebar-headline">Profile settings</p>
            <button class="settings-sidebar-button">My Account</button>
            <button class="settings-sidebar-button">Change border</button>
            <p class="settings-sidebar-headline">General settings</p>
            <button class="settings-sidebar-button">Audio and Music</button>
        </div>
    </div>
</body>
</html>