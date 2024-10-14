<?php
require "avoid_errors.php";
// Uploads banner image to server
if ( 0 < $_FILES['file']['error'] ) {
    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
    // Changes file name to avoid 2 files with same name
    $file_name = $_FILES['file']['name'];
    $temp = explode(".", $file_name);
    $newfilename = round(microtime(true)) . '.' . end($temp);
    $folder = '../img/profile_banners/'.$newfilename;
    $file_name = $newfilename;

    // Uploads banner to server
    move_uploaded_file($_FILES['file']['tmp_name'], $folder);
    $sql = "UPDATE users SET profile_banner='$file_name' WHERE user_id=" . $_SESSION['user_id'];
    $result = $conn->query($sql);
}
?>