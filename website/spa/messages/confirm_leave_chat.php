<style><?php include "./css/confirm-leave-chat.css" ?></style>
<div class="leave-chat-container">
    <h1>Are you sure you want to leave this groupchat?</h1>
    <div class="leave-chat-button-container">
        <button onclick="leaveGroupchat()" title="Leave groupchat">Leave groupchat</button>
        <button id="cancel-button" onclick="ajaxGet('./spa/messages/groupchat_settings.php', 'dark-container')" title="Cancel">Cancel</button>
    </div>
</div>