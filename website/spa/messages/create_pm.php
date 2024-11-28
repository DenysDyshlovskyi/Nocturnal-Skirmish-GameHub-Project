<!-- Reusing styling for creating groupchat -->
<style><?php include "./css/create-groupchat.css" ?></style>
<div class="create-groupchat-container">
    <h1 class="create-groupchat-headline">Send private message to friends</h1>
    <input type="text" class="create-groupchat-search-input" oninput="createGroupchatSearch(this.value)" placeholder="Search in friend list...">
    <div class="create-groupchat-result-container" id="create-groupchat-result-container">
        <?php include "../../php_scripts/load_friend_list_pm.php" ?>
    </div>
    <div class="create-groupchat-button-container">
        <button id="cancel-button" onclick="removeDarkContainer()">Cancel</button>
    </div>
    <form method="POST" id="create-groupchat-form"></form>
</div>