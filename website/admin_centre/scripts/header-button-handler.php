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
            echo $rowAmount . " rows deleted from temp_profilepic table <br><br>";

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

            // Third, remove any duplicate rows in tables where duplicate rows could occur.
            echo "Deleting duplicate rows.....<br>";
            //redeemed_codes
            $stmt = $conn->prepare("DELETE FROM redeemed_codes USING redeemed_codes, redeemed_codes as vtable WHERE (redeemed_codes.id < vtable.id) AND (redeemed_codes.code=vtable.code) AND (redeemed_codes.user_id=vtable.user_id)");
            $stmt->execute();
            echo $stmt->affected_rows ." duplicate rows deleted from redeemed_codes table. <br>";

            //border_inventory
            $stmt = $conn->prepare("DELETE FROM border_inventory USING border_inventory, border_inventory as vtable WHERE (border_inventory.id < vtable.id) AND (border_inventory.border=vtable.border) AND (border_inventory.user_id=vtable.user_id)");
            $stmt->execute();
            echo $stmt->affected_rows ." duplicate rows deleted from border_inventory table. <br>";

            //friend_list
            $stmt = $conn->prepare("DELETE FROM friend_list USING friend_list, friend_list as vtable WHERE (friend_list.id < vtable.id) AND (friend_list.user_id_1=vtable.user_id_1) AND (friend_list.user_id_2=vtable.user_id_2)");
            $stmt->execute();
            echo $stmt->affected_rows ." duplicate rows deleted from friend_list table. <br>";

            //pending_friend_list
            $stmt = $conn->prepare("DELETE FROM pending_friend_list USING pending_friend_list, pending_friend_list as vtable WHERE (pending_friend_list.id < vtable.id) AND (pending_friend_list.user_id_1=vtable.user_id_1) AND (pending_friend_list.user_id_2=vtable.user_id_2) AND (pending_friend_list.sent=vtable.sent)");
            $stmt->execute();
            echo $stmt->affected_rows ." duplicate rows deleted from pending_friend_list table. <br><br>";

            // Fourth, remove any non existent users from things like list of admins, border inventory or groupchats

            // Function that removes rows with non existant user_id from table in parameter
            function removeFakeUsers($table, $type) {
                require "../../php_scripts/avoid_errors.php";
                echo "Removing rows with non existant users from $table table..... <br>";
                $fakeUserAmount = 0;
                if ($type == 'single') {
                    $stmt = $conn->prepare("SELECT user_id FROM $table");
                } else {
                    $stmt = $conn->prepare("SELECT user_id_1, user_id_2 FROM $table");
                }
                $stmt->execute();
                $result = $stmt->get_result();
                if ($type == 'single') {
                    while ($row = $result->fetch_assoc()) {
                        $stmt2 = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
                        $stmt2->bind_param("s", $row['user_id']);
                        $stmt2->execute();
                        $result2 = $stmt2->get_result();
                        if ((mysqli_num_rows($result2) <= 0)) {
                            $stmt = $conn->prepare("DELETE FROM $table WHERE user_id = ?");
                            $stmt->bind_param("s", $row['user_id']);
                            $stmt->execute();
                            echo "Deleted row with non existant user (user_id: " . $row['user_id'] . ") in $table table. <br>";
                            $fakeUserAmount++;
                        }
                    }
                } else {
                    while ($row = $result->fetch_assoc()) {
                        $stmt2 = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
                        $stmt2->bind_param("s", $row['user_id_1']);
                        $stmt2->execute();
                        $result2 = $stmt2->get_result();
                        if ((mysqli_num_rows($result2) <= 0)) {
                            $stmt = $conn->prepare("DELETE FROM $table WHERE user_id_1 = ?");
                            $stmt->bind_param("s", $row['user_id_1']);
                            $stmt->execute();
                            echo "Deleted row with non existant user (user_id: " . $row['user_id_1'] . ") in $table table. <br>";
                            $fakeUserAmount++;
                        } else {
                            $stmt2 = $conn->prepare("SELECT user_id FROM users WHERE user_id = ?");
                            $stmt2->bind_param("s", $row['user_id_2']);
                            $stmt2->execute();
                            $result2 = $stmt2->get_result();
                            if ((mysqli_num_rows($result2) <= 0)) {
                                $stmt = $conn->prepare("DELETE FROM $table WHERE user_id_2 = ?");
                                $stmt->bind_param("s", $row['user_id_2']);
                                $stmt->execute();
                                echo "Deleted row with non existant user (user_id: " . $row['user_id_2'] . ") in $table table. <br>";
                                $fakeUserAmount++;
                            }
                        }
                    }
                }
                if ($fakeUserAmount == 0) {
                    echo "No non existant user ids in $table table <br><br>";
                } else {
                    echo "Removed $fakeUserAmount rows with non existant user ids from $table table <br><br>";
                }
            };

            removeFakeUsers('recovery_codes', 'single');
            removeFakeUsers('redeemed_codes', 'single');
            removeFakeUsers('border_inventory', 'single');
            removeFakeUsers('ip_adresses', 'single');
            removeFakeUsers('friend_list', 'multiple');
            removeFakeUsers('pending_friend_list', 'multiple');

            echo "<br>Cleanup done! <a href='../dashboard.php'>Back to dashboard.</a>";
        }
    }
} else {
    header("Location: ../admin_login.php?error=unauth");
    exit;
}