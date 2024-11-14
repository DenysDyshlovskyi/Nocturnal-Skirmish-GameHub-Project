<?php
// Loads messages from current table.
require "avoid_errors.php";

if (!isset($_SESSION['current_table'])) {
    echo "<p>Select a chat to begin</p>";
} else {
    $conn -> select_db("gamehub_messages");
    $tablename = $_SESSION['current_table'];

    $stmt = $conn->prepare("SELECT * FROM $tablename");
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows != 0){
        while ($row = mysqli_fetch_assoc($result)) {
            $conn -> select_db("gamehub");
            $stmt2 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt2->bind_param("s", $row['user_id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = mysqli_fetch_assoc($result2);
            echo "<div class='message-container'>
                    <div class='message-name-container'>
                        <div class='message-profilepic' style='background-image: url(./img/profile_pictures/" . $row2['profile_picture'] . ");'>
                            <img src='./img/borders/" . $row2['profile_border'] . "'>
                        </div>
                        <h1 class='message-nickname'>" . $row2['nickname'] . " - <i>" . $row['timestamp'] . "</i></h1>
                    </div>
                    <div class='message-content'>
                        <p>" . $row['message'] . "</p>
                    </div>
                </div>";
        }
    } else {
        echo "Looks like this chat is empty....";
    }
    $stmt->close();
    $conn -> select_db("gamehub");
}