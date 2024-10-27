<?php
// Uploads profile pic image to server
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    require "compress.php";
    //If uploaded file is empty
    if (!isset($_FILES['file'])) {
        echo "empty";
    } else {
        $file_type = $_FILES['file']['type'];
        $allowed = array("image/jpeg", "image/png");
        //If file is not jpeg
        if (!in_array($file_type, $allowed)) {
            echo "unsupported";
        } else {
            // Changes file name to avoid 2 files with same name
            $file_name = $_FILES['file']['name'];
            $temp = explode(".", $file_name);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $folder = '../img/temp/'.$newfilename;

            // Uploads profile pic to server
            if (move_uploaded_file($_FILES['file']['tmp_name'], $folder)) {
                $sql = "INSERT INTO temp_profilepic (name, expire) VALUES ('$newfilename' , NOW() + INTERVAL 15 MINUTE)";
                $conn->query($sql);

                //Compresses profile pic image
                compress($folder, $folder, 80);

                // Pass path to temp profile pic to javascript
                $_SESSION['temp_profile_pic'] = './img/temp/' . $newfilename;
            } else {
                echo "error";
            };
        };
    };
} else {
    header("Location: ../index.php");
}
