<?php
// Gets every user profile that includes the input from admin in search bar in friends list add page
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "../../php_scripts/avoid_errors.php";
    if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
        header("Location: ../admin_login.php?error=unauth");
    } else {
        $searchQuery = htmlspecialchars($_POST['search']);

        // Uses % wildcard to get every username, user id and nickname that includes the term, not the users that match the term perfectly
        $stmt = $conn->prepare("SELECT * FROM users WHERE lower(username) LIKE CONCAT('%', ?, '%') OR lower(nickname) LIKE CONCAT('%', ?, '%') OR user_id = ? LIMIT 15");
        $stmt->bind_param("sss", $searchQuery, $searchQuery, $searchQuery);
        $stmt->execute();
        $result = $stmt->get_result();

        if ((mysqli_num_rows($result) <= 0)) {
            echo "none";
        } else {
            while ($row = $result->fetch_assoc()) {
                echo "  <div class='search-result-container'>
                            <div class='search-result-profilepic' style='background-image: url(../img/profile_pictures/" . $row['profile_picture'] . ");'>
                                <img src='../img/borders/" . $row['profile_border'] . "'>
                            </div>
                            <div class='search-result-name-container'>
                                <h1>" . $row['username'] . "</h1>
                                <p>User ID: " . $row['user_id'] . " - " . $row['nickname'] . "</p>
                            </div>
                            <button class='user-search-button' form='display-profile-form' name='profile' value='" . $row['user_id'] . "' title='Edit user'></button>
                        </div>";
            }
        }
        $stmt->close();
    }
} else {
    header("Location: ../../index.php");
}