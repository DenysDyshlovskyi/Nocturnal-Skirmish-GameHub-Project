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
                        <?php include "../../php_scripts/load_online_friends.php" ?>
                    </div>
                </div>
                <div class="hub-friends-list-inner-half">
                    <h1 class="hub-friends-list-inner-half-headline">Offline friends <img src="./img/icons/offline.svg" alt="Offline friends"></h1>
                    <div class="hub-friends-list-scroll-container">
                        <?php include "../../php_scripts/load_offline_friends.php" ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
