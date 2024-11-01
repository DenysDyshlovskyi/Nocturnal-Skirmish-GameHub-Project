<?php
// Handles actions for button presses in header in dashboard.php
session_start();

// If user is unauthorized, redirect them
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../admin_login.php?error=unauth");
    } else {
        if (isset($_POST['cleanup'])) {
            require "../../config/conn.php";
            echo "Performing cleanup... <br>";

            // First, remove any rows in any table with an "expire" column if its past its expiration date.
            echo "Removing expired rows and files... <br>";
            //recovery_codes
            $stmt = $conn->prepare("DELETE FROM recovery_codes WHERE expire < NOW()");
            $stmt->execute();
            echo $stmt->affected_rows ." rows deleted from recovery_codes table. <br><br>";

            //temp_profilepic
            $stmt = $conn->prepare("SELECT * FROM temp_profilepic WHERE expire < NOW()");
            $stmt->execute();
            $result = $stmt->get_result();

            $rowAmount = 0;

            if ((mysqli_num_rows($result) <= 0)) {
                echo "No rows in temp_profilepic. <br>";
            } else {
                while ($row = $result->fetch_assoc()) {
                    $picture_name = "../../img/temp/" . $row['name'];
                    if (file_exists($picture_name)) {
                        unlink($picture_name);
                        echo "Deleted file: " . $picture_name . "<br>";
                    } else {
                        echo "Row found but file doesnt exist. <br>";
                    }
                    echo "Deleting row.... <br>";
                    $stmt = $conn->prepare("DELETE FROM temp_profilepic WHERE name = ? AND expire = ?");
                    $stmt->bind_param("ss", $row['name'], $row['expire']);
                    $stmt->execute();
                    echo "Row deleted. <br><br>";
                    $rowAmount++;
                }
            }
            echo $rowAmount . " amount of rows deleted from temp_profilepic table <br><br>";

            // Second, remove any unused profile pictures or banners.
            echo "Removing unused profile pictures.... <br>";
            $profilePictureAmount = 0;
            $profilepics = array_diff(scandir(dirname(dirname(dirname(__FILE__))) . "/img/profile_pictures"), array('..', '.'));
            foreach($profilepics as $file) {
                $stmt = $conn->prepare("SELECT * FROM users WHERE profile_picture = ?");
                $stmt->bind_param("s", $file);
                $stmt->execute();
                $result = $stmt->get_result();
                if ((mysqli_num_rows($result) <= 0)) {
                    if ($file != "defaultprofile.svg") {
                        $file_path = dirname(dirname(dirname(__FILE__))) . "/img/profile_pictures/" . $file;
                        unlink($file_path);
                        echo "Deleted picture: " . $file_path . "<br>";
                        $profilePictureAmount++;
                    }
                }
            };

            echo "Deleted " . $profilePictureAmount . " profile pictures. <br><br>";

            echo "Removing unused banners.... <br>";
            $bannerAmount = 0;
            $banners = array_diff(scandir(dirname(dirname(dirname(__FILE__))) . "/img/profile_banners"), array('..', '.'));
            foreach($banners as $file) {
                $stmt = $conn->prepare("SELECT * FROM users WHERE profile_banner = ?");
                $stmt->bind_param("s", $file);
                $stmt->execute();
                $result = $stmt->get_result();
                if ((mysqli_num_rows($result) <= 0)) {
                    if ($file != "defaultbanner.jpg") {
                        $file_path = dirname(dirname(dirname(__FILE__))) . "/img/profile_banners/" . $file;
                        unlink($file_path);
                        echo "Deleted picture: " . $file_path . "<br>";
                        $bannerAmount++;
                    }
                }
            };

            echo "Deleted " . $bannerAmount . " banners. <br><br>";

            // Third, remove any duplicate rows.

            // Fourth, remove any non existent users from things like list of admins, border inventory or groupchats (for the future)
            echo "<br>Cleanup done! <a href='../dashboard.php'>Back to dashboard.</a>";
        }
    }
} else {
    header("Location: ../admin_login.php?error=unauth");
    exit;
}
?>