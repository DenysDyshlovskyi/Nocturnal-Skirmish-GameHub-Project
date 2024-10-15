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
        if (move_uploaded_file($_FILES['file']['tmp_name'], $folder)) {
            //Delete old banner first to save storage
            $sql = "SELECT profile_banner FROM users WHERE user_id=" . $_SESSION['user_id'];
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $deleteBanner = '../img/profile_banners/' . $row['profile_banner'];
            if ($row['profile_banner'] != "defaultbanner.jpg") {
                if (file_exists($deleteBanner)) {
                    unlink($deleteBanner);
                };
            };

            // Update database with new banner file
            $sql = "UPDATE users SET profile_banner='$file_name' WHERE user_id=" . $_SESSION['user_id'];
            $result = $conn->query($sql);
            // Pass path to new banner to javascript
            echo 'url(./img/profile_banners/' . $newfilename . ")";
        } else {
            echo "error";
        };
    };
};
?>