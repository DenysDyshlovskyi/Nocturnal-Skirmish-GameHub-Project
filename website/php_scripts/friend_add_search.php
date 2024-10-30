<?php
// Gets every user profile that includes the input from user in search bar in friends list add page
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $searchQuery = htmlspecialchars($_POST['search']);

    // Uses % wildcard to get every username and nickname that includes the term, not the users that match the term perfectly
    $stmt = $conn->prepare("SELECT * FROM users WHERE lower(username) LIKE CONCAT('%', ?, '%') OR lower(nickname) LIKE CONCAT('%', ?, '%') LIMIT 10");
    $stmt->bind_param("ss", $searchQuery, $searchQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    if ((mysqli_num_rows($result) <= 0)) {
        echo "none";
    } else {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='hub-add-friends-search-results-profile'>
                    <a href='#' onclick='displayUserProfile(" . $row['user_id'] . ")' class='hub-add-friends-search-results-profilepic-link'>
                        <div class='hub-add-friends-search-results-profilepic' style='background-image: url(./img/profile_pictures/" . $row['profile_picture'] . ");'>
                            <img src='./img/borders/" . $row['profile_border'] . "'>
                        </div>
                    </a>
                    <div class='hub-add-friends-search-results-profile-name-container'>
                        <h1>" . $row['nickname'] . "</h1>
                        <div class='hub-add-friends-search-results-profile-name-line'></div>
                    </div>
                    <button title='Send friend request' onclick='sendFriendRequest(" . $row['user_id'] . ")'></button>
                </div>
                <div class='hub-add-friends-search-results-profile-divider'></div>
            ";
        }
    }
    $stmt->close();
} else {
    header("Location: ../../index.php");
}