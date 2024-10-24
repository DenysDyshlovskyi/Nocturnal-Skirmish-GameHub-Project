<?php
include "../../php_scripts/avoid_errors.php";
?>
<style> <?php include "./css/friends-list.css" ?> </style>
<style>
    #hub-spa-container {
        display: block;
    }
</style>
<div class="hub-friends-container">
    <button class="hub-friends-backtohub" onclick="displaySpaContainerHub('none')">Back to hub</button>
    <div class="hub-friends-menu">
        <div class="hub-friends-menu-top">Friends</div>
        <button>Friends list</button>
        <button>Add friends</button>
    </div>
    <div id="hub-friends-content">
        <div class="hub-friends-list">
            <h1 class="hub-friends-content-headline">Friend List</h1>
            <div class="hub-friends-list-inner">
                <div class="hub-friends-list-inner-half" id="hub-friends-list-inner-half-left">
                    <h1 class="hub-friends-list-inner-half-headline">Online friends <img src="./img/icons/online.svg" alt="Online friends"></h1>
                    <div class="hub-friends-list-scroll-container">
                        <?php
                            //Gets every user in your friends list.
                            $sql = "SELECT * FROM friend_list WHERE user_id_1 = " . $_SESSION['user_id'];
                            $result = $conn->query($sql);
                            if ((mysqli_num_rows($result) <= 0)) {
                                echo "No friends";
                            } else {
                                echo "friends";
                            }
                        ?>
                        <div class="hub-friends-list-profile-container">
                            <a href="#" class="hub-friends-list-profilepic-link">
                                <div class="hub-friends-list-profilepic" style="background-image: url(<?php echo $_SESSION['user_profile_picture'] ?>);">
                                    <img src="<?php echo $_SESSION['user_profile_border'] ?>" alt="">
                                </div>
                            </a>
                            <div class="hub-friends-list-profile-name-container">
                                <h1>BimBomSlimSlom</h1>
                                <div class="hub-friends-list-profile-name-container-line"></div>
                            </div>
                            <button class="hub-friends-list-profile-message-button"></button>
                        </div>
                    </div>
                </div>
                <div class="hub-friends-list-inner-half">
                    <h1 class="hub-friends-list-inner-half-headline">Offline friends <img src="./img/icons/offline.svg" alt="Offline friends"></h1>
                    <div class="hub-friends-list-scroll-container"></div>
                </div>
            </div>
        </div>
    </div>
</div>
