<?php
//DOESNT WORK
$filename = $_FILES['file']['name'];
$location = "img/profile_banners/" . $filename;
$imageFileType = pathinfo($location,PATHINFO_EXTENSION);
$valid_extensions = array("jpg","jpeg");

// Check file extension
if(!in_array(strtolower($imageFileType),$valid_extensions) ) {
    echo "Invalid file type!";
} else {
    // Upload file
    if(move_uploaded_file($_FILES['file']['tmp_name'],$location)){
        echo $location;
    } else {
        echo "Something went wrong.";
    }
}
?>