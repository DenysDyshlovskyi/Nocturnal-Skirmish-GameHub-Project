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

// GET request with ajax
function ajaxGet(phpFile, changeID){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        document.getElementById(changeID).innerHTML = this.responseText;
        if (changeID == "dark-container") {
            $("#dark-container").fadeIn(100);
        }
    }
    xhttp.open("GET", phpFile);
    xhttp.send();
}

//Removes dark container and its contents
function removeDarkContainer() {
    container = document.getElementById("dark-container");
    if (window.getComputedStyle(container).display != 'none') {
        container.innerHTML = "";
        container.style.display = 'none';
    }
}

// Adds specified border to users inventory
function addBorder(user_id, border) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/add_border.php',
        data:{ user_id : user_id, border : border}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                removeDarkContainer();
                showConfirm("Border " + border + " added to inventory! (Refresh to see changes)");
            }
        }
    })
}

// saves username
function saveUsername(user_id) {
    var username = document.getElementById("username-input-change").value;
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/save_username.php',
        data:{ user_id : user_id, new_username : username}, 
        success: function(response){
            if (response == "taken") {
                showConfirm("Username taken!")
            } else {
                var usernameContainer = document.getElementById("username-h1");
                usernameContainer.innerHTML = "Username: " + username;
                removeDarkContainer();
                showConfirm("Username changed to " + username);
            }
        }
    })
}

// Saves nickname
function saveNickname(user_id) {
    var nickname = document.getElementById("nickname-input-change").value;
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/save_nickname.php',
        data:{ user_id : user_id, new_nickname : nickname}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var nicknameContainer = document.getElementById("nickname-p");
                nicknameContainer.innerHTML = "Nickname: " + nickname;
                removeDarkContainer();
                showConfirm("Nickname changed to " + nickname);
            }
        }
    })
}

// Saves rune amount
function changeRuneAmount(user_id) {
    var runes = document.getElementById("rune-amount-change").value;
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/save_runes.php',
        data:{ user_id : user_id, new_runes : runes}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var runesContainer = document.getElementById("runes-p");
                runesContainer.innerHTML = "Runes: " + runes;
                removeDarkContainer();
                showConfirm("Amount of runes changed to " + runes);
            }
        }
    })
}

// Saves joindate
function changeJoinDate(user_id) {
    var joindate = document.getElementById("joindate-input").value;
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/save_joindate.php',
        data:{ user_id : user_id, new_joindate : joindate}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var joindateContainer = document.getElementById("joindate-p");
                joindateContainer.innerHTML = "Join date: " + joindate;
                removeDarkContainer();
                showConfirm("Join date changed to " + joindate);
            }
        }
    })
}

// Saves email
function saveEmail(user_id) {
    var email = document.getElementById("email-input").value;
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/save_email.php',
        data:{ user_id : user_id, new_email : email}, 
        success: function(response){
            if (response == "taken") {
                showConfirm("Email already registered!.")
            } else {
                var emailContainer = document.getElementById("email-p");
                emailContainer.innerHTML = "E-mail: " + email;
                removeDarkContainer();
                showConfirm("Email changed to " + email);
            }
        }
    })
}

// Saves passowrd
function savePassword(user_id) {
    var password = document.getElementById("password-input").value;
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/save_password.php',
        data:{ user_id : user_id, new_password : password}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                removeDarkContainer();
                showConfirm("Password changed to " + password);
            }
        }
    })
}

// Removes redeemed code
function removeRedeemed(row_id){
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/remove_redeemed_code.php',
        data:{row_id : row_id}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var redeemedContainer = document.getElementById("redeemedComponent_" + row_id);
                redeemedContainer.remove();
                showConfirm("Removed redeemed code: " + response);
            }
        }
    })
}

// Removes all redeemed codes
function removeAllRedeemed(user_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/remove_all_redeemed_codes.php',
        data:{user_id : user_id}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                var redeemedContainer = document.getElementById("redeemed_codes");
                redeemedContainer.innerHTML = "No redeemed codes. <br>";
                showConfirm("Removed all redeemed codes.");
            }
        }
    })
}

// Changes border
function changeBorder(user_id, border) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/change_border.php',
        data:{user_id : user_id, border : border}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                removeDarkContainer();
                showConfirm("Changed border! Refresh to see change");
            }
        }
    })
}