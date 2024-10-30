<style>
    #friends_list_menu_button {
        filter: brightness(0.7);
    }
</style>
<style> <?php include "./css/online-offline-friends.css" ?> </style>
<div class="hub-friends-list">
    <h1 class="hub-friends-content-headline">Friend List</h1>
    <div class="hub-friends-list-inner">
        <div class="hub-friends-list-inner-half" id="hub-friends-list-inner-half-left">
            <h1 class="hub-friends-list-inner-half-headline">Online friends <img src="./img/icons/online.svg" alt="Online friends"></h1>
            <div class="hub-friends-list-scroll-container" id="hub-friends-online-container">
                <?php include "../../php_scripts/load_online_friends.php" ?>
            </div>
        </div>
        <div class="hub-friends-list-inner-half">
            <h1 class="hub-friends-list-inner-half-headline">Offline friends <img src="./img/icons/offline.svg" alt="Offline friends"></h1>
            <div class="hub-friends-list-scroll-container" id="hub-friends-offline-container">
                <?php include "../../php_scripts/load_offline_friends.php" ?>
            </div>
        </div>
    </div>
</div>