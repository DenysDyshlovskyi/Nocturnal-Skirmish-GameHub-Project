<?php
require "avoid_errors.php";
// Uploads cropped image to server
if(isset($_POST['image'])){
    // Deletes expired temp pictures
    $sql = "SELECT * FROM temp_profilepic WHERE expire < NOW()";
    $result = $conn->query($sql);
    if ($result == false) {
        echo "error";
        exit;
    } else {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $deletePic = '../img/temp/' . $row['name'];
                if (file_exists($deletePic)) {
                    unlink($deletePic);
                };
            };
        };
    };
    
    //Deletes temp profile pic
    $deletePic = "." . $_SESSION['temp_profile_pic'];
    if (file_exists($deletePic)) {
        unlink($deletePic);
    };

    //Converts blob from cropper js to jpg
	$data = $_POST['image'];
	$image_array_1 = explode(";", $data);
	$image_array_2 = explode(",", $image_array_1[1]);
	$data = base64_decode($image_array_2[1]);

    $newfilename = round(microtime(true)) . '.jpg';
    $folder = '../img/profile_pictures/'.$newfilename;
    $file_name = $newfilename;

    // Deletes old profile pic
    $sql = "SELECT profile_picture FROM users WHERE user_id=" . $_SESSION['user_id'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $deleteProfilePic = '../img/profile_pictures/' . $row['profile_picture'];
    if ($row['profile_picture'] != "defaultprofile.svg") {
        if (file_exists($deleteProfilePic)) {
            unlink($deleteProfilePic);
        };
    };

	if (file_put_contents($folder, $data)){
        // Update database
        $stmt = $conn->prepare("UPDATE users SET profile_picture = ? WHERE user_id = ?");
        $stmt->bind_param("ss", $file_name, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();

        //Sends image path back to javascript
        echo "url(./img/profile_pictures/" . $newfilename . ")";
        $_SESSION['user_profile_picture'] = "./img/profile_pictures/" . $file_name;
    } else {
        echo "error";
    }
} else {
    echo "error";
}
?>