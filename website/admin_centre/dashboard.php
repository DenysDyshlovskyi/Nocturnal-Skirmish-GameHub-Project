<?php
// Dashboard for admins
session_start();

// If user is unauthorized, redirect them
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: admin_login.php?error=unauth");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub - Admin Center</title>
    <link rel="icon" type=".image/x-icon" href="../img/favicon.png">
    <style> <?php include "../css/universal.css" ?> </style>
    <style> <?php include "./css/dashboard.css" ?> </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <header>
        <h1>GameHub Admin Center</h1>
        <div class="header-button-container">
            <form action="./scripts/header-button-handler.php" method="POST">
                <button name="phpmyadmin">phpMyAdmin</button>
                <button name="cleanup">Perform cleanup</button>
                <button name="logout">Log out.</button>
            </form>
        </div>
    </header>
    <div class="content">
    <form action="display_profile.php" method="POST" id="display-profile-form"></form>
        <div class="user-search-container">
            <h1 class="user-search-headline">User search:</h1>
            <input type="text" placeholder="User ID, Username or Nickname" id="user-search-input" onkeyup="adminUserSearch(this.value)">
            <div class="user-search-results" id="user-search-results">
                Start searching...
            </div>
        </div>
    </div>
</body>
<script><?php include "./js/dashboard.js" ?></script>
</html>