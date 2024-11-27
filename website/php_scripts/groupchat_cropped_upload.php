<?php
// Uploads cropped groupchat image to server
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
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

    // Deletes expired database entries
    $sql = "DELETE FROM temp_profilepic WHERE expire < NOW()";
    $result = $conn->query($sql);
    
    //Deletes temp groupchat image
    $deletePic = "." . $_SESSION['temp_groupchat_image'];
    if (file_exists($deletePic)) {
        unlink($deletePic);
    };

    //Converts blob from cropper js to jpg
	$data = $_POST['image'];
	$image_array_1 = explode(";", $data);
	$image_array_2 = explode(",", $image_array_1[1]);
	$data = base64_decode($image_array_2[1]);

    $newfilename = round(microtime(true)) . '.jpg';
    $folder = '../img/groupchat_images/'.$newfilename;
    $file_name = $newfilename;

    // Deletes old groupchat image
    $tablename = $_SESSION['current_table'];
    $sql = "SELECT groupchat_image FROM groupchat_settings WHERE tablename='$tablename'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $deleteGroupchatImage = '../img/groupchat_images/' . $row['groupchat_image'];
    if ($row['groupchat_image'] != "defaultgroupchat.svg") {
        if (file_exists($deleteGroupchatImage)) {
            unlink($deleteGroupchatImage);
        };
    };

	if (file_put_contents($folder, $data)){
        // Update database
        $stmt = $conn->prepare("UPDATE groupchat_settings SET groupchat_image = ? WHERE tablename = ?");
        $stmt->bind_param("ss", $file_name, $tablename);
        $stmt->execute();
        $stmt->close();

        //Sends image path back to javascript
        echo "url(./img/groupchat_images/" . $newfilename . ")";
    } else {
        echo "error";
    }
} else {
    header("Location: ../index.php");
}