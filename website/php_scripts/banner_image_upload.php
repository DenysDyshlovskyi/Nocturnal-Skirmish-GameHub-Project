<?php
require "avoid_errors.php";
// Uploads banner image to server

//If uploaded file is empty
if (!isset($_FILES['file'])) {
    echo "empty";
} else {
    $file_type = $_FILES['file']['type'];
    $allowed = array("image/jpeg");
    //If file is not jpeg
    if (!in_array($file_type, $allowed)) {
        echo "unsupported";
    } else {
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
        echo 'url(./img/profile_banners/' . $newfilename . ")";
    }
};
?>