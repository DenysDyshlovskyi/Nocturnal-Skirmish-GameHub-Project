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
            // Checks if user is yourself
            if ($row['user_id'] != $_SESSION['user_id']) {
                // Checks if user is already in friends list
                $stmt = $conn->prepare("SELECT user_id_2 FROM friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
                $stmt->bind_param("ss", $_SESSION['user_id'], $row['user_id']);
                $stmt->execute();
                $result2 = $stmt->get_result();
                if ((mysqli_num_rows($result2) <= 0)) {
                    $isInFriendsList = 0;
                } else {
                    $isInFriendsList = 1;
                }

                // Checks if you have already sent friend request to user
                $stmt = $conn->prepare("SELECT user_id_2 FROM pending_friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
                $stmt->bind_param("ss", $_SESSION['user_id'], $row['user_id']);
                $stmt->execute();
                $result2 = $stmt->get_result();
                if ((mysqli_num_rows($result2) <= 0)) {
                    $alreadySent = 0;
                } else {
                    $alreadySent = 1;
                }

                // Checks if user has already sent friend request to you
                $stmt = $conn->prepare("SELECT user_id_2 FROM pending_friend_list WHERE user_id_1 = ? AND user_id_2 = ?");
                $stmt->bind_param("ss", $row['user_id'], $_SESSION['user_id']);
                $stmt->execute();
                $result2 = $stmt->get_result();
                if ((mysqli_num_rows($result2) <= 0)) {
                    $alreadyReceived = 0;
                } else {
                    $alreadyReceived = 1;
                }

                if ($isInFriendsList == 0 && $alreadySent == 0 && $alreadyReceived == 0) {
                    $friendRequestButton = "<button title='Send friend request' onclick='sendFriendRequest(" . $row['user_id'] . ", %s)'></button>";
                } else {
                    if ($isInFriendsList == 1) {
                        $friendRequestButton = "<button title='You are already friends with this user.' id='already_friends'></button>";
                    } else if ($alreadySent == 1){
                        $friendRequestButton = "<button title='You have already sent this user a friend request.' id='already_friends'></button>";
                    } else {
                        $friendRequestButton = "<button title='This user has already sent you a friend request' id='already_friends'></button>";
                    }
                }

                printf("<div class='hub-add-friends-search-results-profile'>
                        <a href='#' onclick='displayUserProfile(" . $row['user_id'] . ")' class='hub-add-friends-search-results-profilepic-link'>
                            <div class='hub-add-friends-search-results-profilepic' style='background-image: url(./img/profile_pictures/" . $row['profile_picture'] . ");'>
                                <img src='./img/borders/" . $row['profile_border'] . "'>
                            </div>
                        </a>
                        <div class='hub-add-friends-search-results-profile-name-container'>
                            <h1>" . $row['nickname'] . "</h1>
                            <div class='hub-add-friends-search-results-profile-name-line'></div>
                        </div>
                        $friendRequestButton
                    </div>
                    <div class='hub-add-friends-search-results-profile-divider'></div>
                    ", '"' . $row['nickname'] . '"');
            }
        }
    }
    $stmt->close();
} else {
    header("Location: ../../index.php");
}