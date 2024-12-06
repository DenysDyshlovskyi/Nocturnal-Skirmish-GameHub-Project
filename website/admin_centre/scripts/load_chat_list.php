<?php
// Loads a list of all chats for admins
require "../php_scripts/avoid_errors.php";
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
} else {
    // Get all chats that are two user
    $stmt = $conn->prepare("SELECT * FROM chats WHERE type = 'two_user' ORDER BY user_id");
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "No two user chats...";
    } else {
        // Echos a headline for the table
        echo "<th colspan='3' class='chat-table-headline'>Two User</th>
                <tr>
                    <td><b>Name/Users</b></td>
                    <td><b>Amount of members</b></td>
                    <td><b>See Chat</b></td>
                </tr>";
        // Array containng tables already loaded in to avoid duplicates
        $alreadyAccessed = array();
        while ($row = $result->fetch_assoc()) {
            // Get information about both users
            // First user
            if (!in_array($row['tablename'], $alreadyAccessed)) {
                array_push($alreadyAccessed, $row['tablename']);
                $stmt2 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
                $stmt2->bind_param("s", $row['user_id']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if ((mysqli_num_rows($result2) <= 0)) {
                    // If user doesnt exist, show deleted user
                    $user_id_1_profilepic = "defaultprofile.svg";
                    $user_id_1_border = "defaultborder.webp";
                    $user_id_1_userid = $row2['user_id'];
                    $user_id_1_username = "Deleted User";
                    $user_id_1_nickname = "Deleted User";
                } else {
                    $row2 = $result2->fetch_assoc();
                    $user_id_1_profilepic = $row2['profile_picture'];
                    $user_id_1_border = $row2['profile_border'];
                    $user_id_1_userid = $row2['user_id'];
                    $user_id_1_username = $row2['username'];
                    $user_id_1_nickname = $row2['nickname'];
                }
                // Second user
                // Get user id from same chat that isnt the first one
                $stmt2 = $conn->prepare("SELECT * FROM chats WHERE tablename = ? AND user_id <> ?");
                $stmt2->bind_param("ss", $row['tablename'], $user_id_1_userid);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $row2 = $result2->fetch_assoc();

                // Get information about that user
                $stmt2 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
                $stmt2->bind_param("s", $row2['user_id']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if ((mysqli_num_rows($result2) <= 0)) {
                    // If user doesnt exist, show deleted user
                    $user_id_2_profilepic = "defaultprofile.svg";
                    $user_id_2_border = "defaultborder.webp";
                    $user_id_2_username = "Deleted User";
                    $user_id_2_nickname = "Deleted User";
                    $user_id_2_userid = $row2['user_id'];
                } else {
                    $row2 = $result2->fetch_assoc();
                    $user_id_2_profilepic = $row2['profile_picture'];
                    $user_id_2_border = $row2['profile_border'];
                    $user_id_2_username = $row2['username'];
                    $user_id_2_nickname = $row2['nickname'];
                    $user_id_2_userid = $row2['user_id'];
                }
                // Tags that the row can be searched by
                $searchTags = $user_id_1_userid . "_" . $user_id_2_userid . "_" . $user_id_1_nickname . "_" . $user_id_2_nickname . "_" . $user_id_1_username . "_" . $user_id_2_username . "_" . $row['tablename'];
                // Echo the row into the table
                printf("<tr id='$searchTags'><td class='chat-list-two-user-td'>
                                    <div class='chat-list-two-user-container'>
                                        <div class='chat-list-user-container'>
                                            <div class='chat-list-profilepic' style='background-image: url(../img/profile_pictures/" . $user_id_1_profilepic . ");'>
                                                <img src='../img/borders/" . $user_id_1_border . "'>
                                            </div>
                                            <p class='chat-list-username'>" . $user_id_1_username . "</p>
                                        </div>
                                        <div class='chat-list-user-container'>
                                            <div class='chat-list-profilepic' style='background-image: url(../img/profile_pictures/" . $user_id_2_profilepic . ");'>
                                                <img src='../img/borders/" . $user_id_2_border . "'>
                                            </div>
                                            <p class='chat-list-username'>" . $user_id_2_username . "</p>
                                        </div>
                                    </div>
                                </td>
                                <td>2</td>
                                <td><button onclick='adminSeeChat(%s)'>See chat</button></td></tr>
                ", '"' . $row['tablename'] . '"');
                }
        }
    }
    $stmt->close();
    $stmt2->close();

    // Get all chats that are groupchats
    $stmt = $conn->prepare("SELECT * FROM chats WHERE type = 'groupchat'");
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "No group chats...";
    } else {
        // Echos a headline for the table
        echo "<th colspan='3' class='chat-table-headline'>Groupchats</th>
                <tr>
                    <td><b>Name/Users</b></td>
                    <td><b>Amount of members</b></td>
                    <td><b>See Chat</b></td>
                </tr>";
        $alreadyAccessed = array();
        while ($row = $result->fetch_assoc()) {
            if (!in_array($row['tablename'], $alreadyAccessed)) {
                array_push($alreadyAccessed, $row['tablename']);
                // Get amount of users in groupchat
                $stmt2 = $conn->prepare("SELECT COUNT(*) AS total FROM chats WHERE tablename = ?");
                $stmt2->bind_param("s", $row['tablename']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                $row2 = $result2->fetch_assoc();
                $groupchatMemberCount = $row2['total'];

                // Get information about everyone in chat for search tags
                $searchTags = "";

                $stmt2 = $conn->prepare("SELECT user_id FROM chats WHERE tablename = ?");
                $stmt2->bind_param("s", $row['tablename']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                while ($row2 = $result2->fetch_assoc()) {
                    $stmt3 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
                    $stmt3->bind_param("s", $row2['user_id']);
                    $stmt3->execute();
                    $result3 = $stmt3->get_result();
                    if ((mysqli_num_rows($result3) <= 0)) {
                        $searchTags = $searchTags . "_0_Deleted User_Deleted User";
                    } else {
                        $row3 = $result3->fetch_assoc();
                        $searchTags = $searchTags . "_" . $row3['user_id'] . "_" . $row3['username'] . "_" . $row3['nickname'];
                    }
                }

                $searchTags = $searchTags . "_" . $row['tablename'];

                // Get information about groupchat
                $stmt2 = $conn->prepare("SELECT * FROM groupchat_settings WHERE tablename = ?");
                $stmt2->bind_param("s", $row['tablename']);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                if ((mysqli_num_rows($result2) <= 0)) {
                    echo "error";
                } else {
                    $row2 = $result2->fetch_assoc();
                    $searchTags = $searchTags . "_" . $row2['groupchat_name'];
                    // Echo the row into the table
                    printf("<tr id='$searchTags'>
                                <td class='chat-list-two-user-td'>
                                    <div class='chat-list-groupchat-container'>
                                        <div class='chat-list-groupchat-image' style='background-image: url(../img/groupchat_images/" . $row2['groupchat_image'] . ");'></div>
                                        <p class='chat-list-groupchat-name'>" . $row2['groupchat_name'] . "</p>
                                    </div>
                                </td>
                                <td>" . $groupchatMemberCount . "</td>
                                <td><button onclick='adminSeeChat(%s)'>See Chat</button></td>
                            </tr>", '"' . $row['tablename'] . '"');
                    // Saves the tablename to not get duplicate rows
                    $previousTable = $row['tablename'];
                }
            }
        }
    }
}