<?php
session_start();
?>
<style>
    #add_friends_menu_button {
        filter: brightness(0.7);
    }
</style>
<style> <?php include "./css/add-friends.css" ?> </style>
<h1 class="hub-friends-content-headline">Add Friends</h1>
<div class="hub-add-friends-container">
    <div class="hub-add-friends-half-container">
        <div class="hub-add-friends-half-container-inner">
            <div class="hub-add-friends-pending-headline">
                <h1>Friend already added you?</h1>
                <div class="hub-add-friends-pending-headline-line"></div>
            </div>
            <p class="hub-add-friends-pending-description">Pending invites</p>
            <div class="hub-add-friends-pending-container" id="hub-add-friends-pending-container">
                <?php include "../../php_scripts/load_pending_invites.php" ?>
            </div>
        </div>
    </div>
    <div class="hub-add-friends-half-container">
        <div class="hub-add-friends-half-container-inner">
        </div>
    </div>
</div>