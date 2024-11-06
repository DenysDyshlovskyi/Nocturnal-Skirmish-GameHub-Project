<?php
// Uploads cropped banner to server
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../../admin_login.php?error=unauth");
        exit;
    } else {
        // Deletes expired temp pictures
        $sql = "SELECT * FROM temp_profilepic WHERE expire < NOW()";
        $result = $conn->query($sql);
        if ($result == false) {
            echo "error";
            exit;
        } else {
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $deletePic = '../../../img/temp/' . $row['name'];
                    if (file_exists($deletePic)) {
                        unlink($deletePic);
                    };
                };
            };
        };

        // Deletes expired database entries
        $sql = "DELETE FROM temp_profilepic WHERE expire < NOW()";
        $result = $conn->query($sql);
        
        //Deletes temp banner pic
        $deletePic = "../../" . $_SESSION['temp_profile_banner'];
        if (file_exists($deletePic)) {
            unlink($deletePic);
        };

        //Converts blob from cropper js to jpg
        $data = $_POST['image'];
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);

        $newfilename = round(microtime(true)) . '.jpg';
        $folder = '../../../img/profile_banners/'.$newfilename;
        $file_name = $newfilename;

        // Deletes old banner
        $sql = "SELECT profile_banner FROM users WHERE user_id=" . $_SESSION['displayprofile_userid'];
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $deleteProfilePic = '../../../img/profile_banners/' . $row['profile_banner'];
        if ($row['profile_banner'] != "defaultbanner.jpg") {
            if (file_exists($deleteProfilePic)) {
                unlink($deleteProfilePic);
            };
        };

        if (file_put_contents($folder, $data)){
            // Update database
            $stmt = $conn->prepare("UPDATE users SET profile_banner = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $file_name, $_SESSION['displayprofile_userid']);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "error";
        }
    };
} else {
    header("Location: ../index.php");
}