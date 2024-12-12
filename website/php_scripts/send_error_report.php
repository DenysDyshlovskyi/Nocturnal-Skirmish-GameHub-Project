<?php
// Stores an error report in the database
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    require "get_user_ip.php";
    $unix_timestamp = time();

    // See if user has already sent an error report in the last 15 minutes
    $stmt = $conn->prepare("SELECT * FROM error_reports WHERE ip = ? AND unix_timestamp > ? OR user_id = ? AND unix_timestamp > ?");
    $stmt->bind_param("ssss", $ip, $unix_timestamp, $_SESSION['user_id'], $unix_timestamp);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) > 0)) {
        echo "toomanysubmits";
        exit;
    }
    $stmt->close();

    $whatHappened = htmlspecialchars($_POST['what-happened-textarea']);
    $category = htmlspecialchars($_POST['category']);

    // Check if the category is empty or invalid
    $categoryArray = array("error_message", "got_stuck", "design_flaw", "other");
    if (!in_array($category, $categoryArray)) {
        echo "empty";
        exit;
    }

    // Check if any inputs are empty
    if ($whatHappened === null || strlen($whatHappened) == 0 || ctype_space($whatHappened)) {
        echo "empty";
        exit;
    }

    // if screenshot was provided, upload 
    if ($_FILES['media-upload']['error'] != 4 || ($_FILES['media-upload']['size'] != 0 && $_FILES['media-upload']['error'] != 0)){
        define('MB', 1048576);
        $file_type = $_FILES['media-upload']['type'];
        $allowed = array("image/jpeg", "image/png");
        //If file is not jpeg or png
        if (!in_array($file_type, $allowed)) {
            echo "unsupported";
            exit;
        } else if ($_FILES['media-upload']['size'] > 3*MB) {
            // If file is over 3MB
            echo "toolarge";
            exit;
        } else {
            // Changes file name to avoid 2 files with same name
            $file_name = $_FILES['media-upload']['name'];
            $temp = explode(".", $file_name);
            $newfilename = round(microtime(true)) . '.' . end($temp);
            $folder = '../img/error_screenshots/'.$newfilename;
            // Uploads screenshot to server
            if (move_uploaded_file($_FILES['media-upload']['tmp_name'], $folder)) {
                $screenshot = $newfilename;
            } else {
                echo "error";
                exit;
            }
        };
    } else {
        $screenshot = null;
    }

    require "getdate.php";
    $timestamp = $date . " " . $time;
    $unix_timestamp = time() + 900;

    // Insert the error report into the database
    $stmt = $conn->prepare("INSERT INTO error_reports (user_id, ip, timestamp, category, what_happened, screenshot, unix_timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $_SESSION['user_id'], $ip, $timestamp, $category, $whatHappened, $screenshot, $unix_timestamp);
    $stmt->execute();
    $stmt->close();
} else {
    header("Location: ../index.php");
}