<?php
// Gives live feedback to user when theyre typing in username
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $username = htmlspecialchars($_POST['username']);

    // String containing all charachters allowed
    $whitelist = "abcdefghijklmnopqrstuvwxyz0123456789_";

    // Convert username to lowercase
    $username_lower = strtolower($username);

    // For each charachter in username, check that the charachter is allowed
    $whitelistColor = "#58D245";
    $whitelistImg = "check-green.svg";
    foreach (str_split($username_lower) as $char) {
        if (!str_contains($whitelist, $char)) {
            $whitelistColor = "#FF4949";
            $whitelistImg = "x-red.svg";
        }
    }

    // Check that username is between 5-25 letters
    $letterCountColor = "#58D245";
    $letterCountImg = "check-green.svg";
    if (strlen($username) > 25 || strlen($username) < 5) {
        $letterCountColor = "#FF4949";
        $letterCountImg = "x-red.svg";
    }

    // Check if username is already taken
    $takenColor = "#58D245";
    $takenImg = "check-green.svg";
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
        $takenColor = "#FF4949";
        $takenImg = "x-red.svg";
    }
    $stmt->close();

    // Check if username is empty or just whitespace
    $emptyColor = "#58D245";
    $emptyImg = "check-green.svg";
    if ($username === null || strlen($username) == 0 || ctype_space($username)) {
        $emptyColor = "#FF4949";
        $emptyImg = "x-red.svg";
    };

    // Echo results to screen
    echo "
    <p style='color: $whitelistColor'><img draggable='false' src='./img/icons/$whitelistImg'>Only includes numbers from 0-9,  letters in english alphabet and these special charachters: _</p>
    <p style='color: $letterCountColor'><img draggable='false' src='./img/icons/$letterCountImg'>Is between 5-25 letters.</p>
    <p style='color: $takenColor'><img draggable='false' src='./img/icons/$takenImg'>Is not taken.</p>
    <p style='color: $emptyColor'><img draggable='false' src='./img/icons/$emptyImg'>Is not empty.</p>
    ";
} else {
    header("Location: ../index.php");
}