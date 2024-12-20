<?php
require "../../php_scripts/avoid_errors.php";
// Script for loading in files and folder in directory
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
} else {
    // Sets a default path if current path is not set
    if (!isset($_SESSION['admin_currentpath'])) {
        $_SESSION['admin_currentpath'] = "C:\inetpub\wwwroot";
    }

    if (!isset($_SESSION['admin_previouspath'])) {
        $_SESSION['admin_previouspath'] = $_SESSION['admin_currentpath'];
    }

    // If the current path is not in the c drive, override it
    if (stripos($_SESSION['admin_currentpath'], 'C:') === false) {
        $_SESSION['admin_currentpath'] = "C:\\";
    } 

    // Scans the current directory
    $directoryScan = scandir($_SESSION['admin_currentpath']);
    $backgroundColorCount = 0;

    // Set current path header
    echo "<p id='file-explorer-path'>Path: " . $_SESSION['admin_currentpath'] . "<button class='previousdir-button' onclick='adminPreviousDir()' title='" . $_SESSION['admin_previouspath'] . "'></button></p>";

    foreach ($directoryScan as $result) {
        // Alternates background color
        if ($backgroundColorCount == 0) {
            $backgroundColorCount = 1;
            $backgroundColor = "#ebebeb";
        } else {
            $backgroundColorCount = 0;
            $backgroundColor = "#b8b8b8";
        }

        if ($result === '.' or $result === '..') continue;

        // If the result is a folder
        if (is_dir($_SESSION['admin_currentpath'] . '/' . $result)) {
            printf("<div class='file-explorer-row' style='background-color: $backgroundColor;'>
                        <img src='../img/icons/folder.svg'><a title='" . $_SESSION['admin_currentpath'] . '/' . $result . "' href='#' onclick='adminDownloadFile(%s)'>$result</a>
                    </div>", '"' . $result . '", "folder"');
        } else {
            // If result is file
            printf("<div class='file-explorer-row' style='background-color: $backgroundColor;'>
                        <img src='../img/icons/file.svg'><a title='" . $_SESSION['admin_currentpath'] . '/' . $result . "' href='#' onclick='adminDownloadFile(%s)'>$result</a>
                    </div>", '"' . $result . '", "file"');
        }
    }
}