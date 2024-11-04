// Removes border when remove button is pressed
function removeBorder(user_id, border) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/remove_border.php',
        data:{ user_id : user_id, border : border }, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var borderContainer = document.getElementById("borderComponent_" + border);
                borderContainer.remove();
                showConfirm("Removed border: " + border)
            }
        }
    })
}

// Removes all borders when remove all button is pressed
function removeAllBorders(user_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/remove_all_borders.php',
        data:{ user_id : user_id}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var borderContainer = document.getElementById("border-inventory");
                borderContainer.innerHTML = "No borders.<br><button onclick='addNewBorder(" + user_id + ")'>Add new border to inventory</button>";
                showConfirm("Removed all borders")
            }
        }
    })
}

//Shows a popup when saving information in user settings
function showConfirm(text) {
    $("#confirmContainer").stop( true, true ).fadeOut();
    confirmContainer = document.getElementById("confirmContainer");
    confirmContainer.innerHTML = text;
    confirmContainer.style.display = "block";
    $("#confirmContainer").fadeOut(3000);
}

// Saves description
function saveDescription(user_id) {
    var description = $('#description-textarea').val();
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/save_description.php',
        data:{ user_id : user_id, description : description}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var descriptionTextArea = document.getElementById("description-textarea");
                descriptionTextArea.innerHTML = response;
                showConfirm("Saved description")
            }
        }
    })
}

// Removes friend from friend list
function removeFriend(user_id_1, user_id_2) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/remove_friend.php',
        data:{ user_id_1 : user_id_1, user_id_2 : user_id_2}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var friendContainer = document.getElementById("friendListComponent_" + user_id_2);
                friendContainer.remove();
                showConfirm("Removed friend (uID = " + user_id_2 + ")");
            }
        }
    })
}

// Removes all friends when remove all button is pressed
function removeAllFriends(user_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/remove_all_friends.php',
        data:{ user_id : user_id}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var friendContainer = document.getElementById("friend_list");
                friendContainer.innerHTML = "No friends.";
                showConfirm("Removed all friends")
            }
        }
    })
}

// Removes outgoing friend request
function removePendingOutgoingFriend(user_id_1, user_id_2, row_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/remove_pending_outgoing_friend.php',
        data:{ user_id_1 : user_id_1, user_id_2 : user_id_2}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var friendContainer = document.getElementById("pendingFriendListComponent_" + row_id);
                friendContainer.remove();
                showConfirm("Removed outgoing friend request (uID = " + user_id_2 + ")");
            }
        }
    })
}

// Removes incoming friend request
function removePendingIncomingFriend(user_id_1, user_id_2, row_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/remove_pending_incoming_friend.php',
        data:{ user_id_1 : user_id_1, user_id_2 : user_id_2}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var friendContainer = document.getElementById("pendingFriendListComponent_" + row_id);
                friendContainer.remove();
                showConfirm("Removed incoming friend request (uID = " + user_id_1 + ")");
            }
        }
    })
}

// Removes all pending friends when remove all button is pressed
function removeAllPendingFriends(user_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/remove_all_pending_friends.php',
        data:{ user_id : user_id}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var friendContainer = document.getElementById("pending_friend_list");
                friendContainer.innerHTML = "No outgoing pending friends. <br> No incoming pending friends.";
                showConfirm("Removed all pending friends")
            }
        }
    })
}