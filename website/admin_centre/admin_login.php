<?php
if (isset($_GET['error'])) {
    if ($_GET['error'] == "empty") {
        echo "<div id='error-container'>Password is empty!</div>";
    } else if ($_GET['error'] == "wrong") {
        echo "<div id='error-container'>Password is wrong!</div>";
    } else if ($_GET['error'] == "unauth") {
        echo "<div id='error-container'>You are unauthorized to use this resource!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type=".image/x-icon" href="../img/favicon.png">
    <style> <?php include "../css/universal.css" ?> </style>
    <style> <?php include "./css/admin-login.css" ?> </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>GameHub - Login as admin</title>
</head>
<body>
    <div class="admin-logon-container">
        <img src="../img/Noc_Skir_Logo.svg" alt="Nocturnal Skirmish Logo">
        <h1>GameHub Admin Center</h1>
        <form action="./scripts/login_admin.php" method="POST">
            <input type="password" name="admin_password" maxlength="30" placeholder="Password" class="admin-logon-input" required> <br>
            <input type="submit" value="Log in" class="admin-logon-button">
        </form>
        <button onclick="window.location.href = '../index.php'" class="admin-logon-button" id="backtologin">Back to login</button>
    </div>
</body>
<script>$('#error-container').delay(2000).fadeOut('slow');</script>
</html>