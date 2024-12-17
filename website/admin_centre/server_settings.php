<?php
// Page for doing diffrent things on the server
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
    <title>GameHub & Nocturnal Skirmish - Server Settings</title>
    <link rel="icon" type=".image/x-icon" href="../img/favicon.png">
    <style> <?php include "../css/universal.css" ?> </style>
    <style> <?php include "./css/server-settings.css" ?> </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../lib/chartjs/dist/chart.umd.js"></script>
</head>
<body onload="loadCharts()">
    <div id="confirmContainer" class="confirmation-popup"></div>
    <header>
        <h1>Server settings</h1>
        <div class="server-settings-header-button-container">
            <button class="git_pull_button" onclick="gitPull()" title="Git pull">Git pull</button>
            <button class="backtodashboard" title="Back to dashboard" onclick="window.location.href = 'dashboard.php'">Back to Dashboard</button>
        </div>
    </header>
    <div class="content">
        <div class="server-stats-container">
            <h1>Server statistics</h1>
            <div class="stats-container">
                <p id="cpu-current-p"></p>
                <div class="chart-container">
                    <canvas id="cpu_chart"></canvas>
                </div>
            </div>
            <div class="stats-container">
                <p id="ram-current-p"></p>
                <div class="chart-container">
                    <canvas id="ram_chart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <script><?php include "./js/server_settings.js" ?></script>
</body>