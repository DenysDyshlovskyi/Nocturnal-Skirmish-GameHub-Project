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
                <button name="cleanup">Perform cleanup</button>
                <button name="logout">Log out.</button>
            </form>
        </div>
    </header>
</body>
</html>