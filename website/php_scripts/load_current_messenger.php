<?php
// Sets nickname in header to the correct messenger
require "avoid_errors.php";

// If a current messenger has not been set
if (!isset($_SESSION['current_messenger'])) {
    echo "No chat selected.";
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['current_messenger']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = mysqli_fetch_assoc($result);
    echo "<a href='#' onclick='displayUserProfile(" . $row['user_id'] . ")'>
                        <div class='current-messenger-profilepic' style='background-image: url(./img/profile_pictures/" . $row['profile_picture'] . ");'>
                            <img src='./img/borders/" . $row['profile_border'] . "'>
                        </div>
                    </a>
                    <div class='current-messenger-name-container'>
                        <p>" . $row['nickname'] . "</p>
                    </div>";
    $stmt->close();
}