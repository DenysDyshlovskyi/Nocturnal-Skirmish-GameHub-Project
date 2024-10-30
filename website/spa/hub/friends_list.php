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
    <button class="hub-friends-backtohub" onclick="displaySpaContainerHub('none'); stopFriendsListInterval();">Back to hub</button>
    <div class="hub-friends-menu">
        <div class="hub-friends-menu-top">Friends</div>
        <button onclick="ajaxGet('./spa/hub/online_offline_friends.php', 'hub-friends-content')" id="friends_list_menu_button">Friends list</button>
        <button onclick="ajaxGet('./spa/hub/add_friends.php', 'hub-friends-content')" id="add_friends_menu_button"><div id="pending_amount"></div>Add friends</button>
    </div>
    <div id="hub-friends-content">
    </div>
</div>
