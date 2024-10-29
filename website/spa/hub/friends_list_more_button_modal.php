<?php
session_start();
?>
<style>
    #dark-container {
        display: block !important;
    }
</style>
<style> <?php include "./css/friends-list-more-button-modal.css" ?> </style>

<div class="friends-list-more-modal-container" id="friends-list-more-modal-container">
    <button class="friends-list-more-modal-exit" onclick="removeDarkContainer()"></button>
    <div class="friends-list-more-modal-profile-container">
        <div class="friends-list-more-modal-profilepic" style="background-image: url(<?php echo "./img/profile_pictures/" . $_SESSION['more_button_profilepic'] ?>);">
            <img src="<?php echo "./img/borders/" . $_SESSION['more_button_border'] ?>" alt="">
        </div>
        <div class="friends-list-more-modal-name-container">
            <h1><?php echo $_SESSION['more_button_nickname'] ?></h1>
        </div>
    </div>
    <div class="friends-list-more-modal-button-container">
        <button class="friends-list-more-modal-remove-button" onclick="confirmFriendRemove()">Remove friend</button>
    </div>
</div>
<div class="friends-list-more-modal-remove-confirm-container" id="friends-list-more-modal-remove-confirm-container">
    <h1>Are you sure you want to remove this friend?</h1>
    <div class="friends-list-more-modal-remove-confirm-button-container">
        <button onclick="removeFriend(<?php echo $_SESSION['more_button_userid'] ?>)">Remove</button>
        <button onclick="confirmFriendRemove()" id="more-modal-cancel-remove">Cancel</button>
    </div>
</div>