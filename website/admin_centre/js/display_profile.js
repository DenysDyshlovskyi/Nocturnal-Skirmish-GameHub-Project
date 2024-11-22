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
                borderContainer.innerHTML = "No borders.<br><button>Refresh to add new border to inventory</button>";
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
function ajaxGet(phpFile, changeID, onLoad){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        document.getElementById(changeID).innerHTML = this.responseText;
        if (changeID == "dark-container") {
            $("#dark-container").fadeIn(100);
        }

        if (onLoad == "cropper_js") {
            configureCropperJS();
        } else if (onLoad == "cropper_js_banner") {
            configureCropperJSBanner();
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

// Uploads temp profile pic to server
function uploadProfilePic() {
    var file_data = $('#profilepic-input').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);                           
    $.ajax({
        url: './scripts/display_profile/profilepic_upload.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(response){
            if (response == "unsupported") {
                removeDarkContainer();
                showConfirm("File type not supported! Only JPG and PNG allowed.");
            } else if (response == "empty") {
                removeDarkContainer();
                showConfirm("File input empty!");
            } else if (response == "error") {
                removeDarkContainer();
                showConfirm("Something went wrong.");
            } else {
                container = document.getElementById("dark-container");
                container.innerHTML = "";
                ajaxGet('./spa/profilepic_crop.php', 'dark-container', 'cropper_js');
            }
        }
    });
};

// Configures settings from cropper js for profile picture
function configureCropperJS() {
    image = document.getElementById('cropper_js_element');
    let cropper = new Cropper(image, {
        aspectRatio: 1/1,
        dragMode: 'none',
        preview: '.settings-profilepic-preview-profilepic'
    });
    $('#settings-profilepic-crop-save-button').click(function(){
		canvas = cropper.getCroppedCanvas({
			width:400,
			height:400
		});
		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
				$.ajax({
					url:'./scripts/display_profile/profilepic_cropped_upload.php',
					method:'POST',
					data:{image:base64data},
					success:function(response){
                        if (response == "error") {
                            showConfirm("Something went wrong.");
                            removeDarkContainer();
                        } else {
                            showConfirm("Profile picture saved! Refresh to see changes.");
                            removeDarkContainer();
                        }
					}
				});
			};
		});
    });
};

// Uploads temp banner to server
function uploadBanner() {
    var file_data = $('#banner-input').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);                           
    $.ajax({
        url: './scripts/display_profile/banner_upload.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(response){
            if (response == "unsupported") {
                removeDarkContainer();
                showConfirm("File type not supported! Only JPG allowed.");
            } else if (response == "empty") {
                removeDarkContainer();
                showConfirm("File input empty!");
            } else if (response == "error") {
                removeDarkContainer();
                showConfirm("Something went wrong.");
            } else {
                container = document.getElementById("dark-container");
                container.innerHTML = "";
                ajaxGet('./spa/banner_crop.php', 'dark-container', 'cropper_js_banner');
            }
        }
    });
};

// Configures settings from cropper js for banner
function configureCropperJSBanner() {
    image = document.getElementById('cropper_js_element_banner');
    let cropper = new Cropper(image, {
        aspectRatio: 93/14,
        dragMode: 'none',
        preview: '.settings-banner-preview-banner'
    });
    $('#settings-banner-crop-save-button').click(function(){
		canvas = cropper.getCroppedCanvas({
			width:930,
			height:140
		});
		canvas.toBlob(function(blob){
			url = URL.createObjectURL(blob);
			var reader = new FileReader();
			reader.readAsDataURL(blob);
			reader.onloadend = function(){
				var base64data = reader.result;
				$.ajax({
					url:'./scripts/display_profile/banner_cropped_upload.php',
					method:'POST',
					data:{image:base64data},
					success:function(response){
                        if (response == "error") {
                            showConfirm("Something went wrong.");
                            removeDarkContainer();
                        } else {
                            showConfirm("Banner saved! Refresh to see changes.");
                            removeDarkContainer();
                        }
					}
				});
			};
		});
    });
};

// If ban type is permanent, hide the date input
function banType(type) {
    var hideShowBanContainer = document.getElementById("hide-show-ban")
    hideShowBanContainer.style.display = "block";
    if (type == "temp") {
        document.getElementById("ban-duration").style.display = "block";
        var now = new Date(),

        // Can only choose dates in future
        minDate = now.toISOString().substring(0,10);
        $('#ban-duration-input').prop('min', minDate);
    } else if (type == "perm") {
        document.getElementById("ban-duration").style.display = "none";
    }
}

// Lifts a ban
function liftBan(row_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/lift_ban.php',
        data:{row_id : row_id}, 
        success: function(response){
            if (response == "error") {
                showConfirm("Something went wrong.")
            } else {
                removeDarkContainer();
                showConfirm("Lifted ban!");
            }
        }
    })
}

function kickUser(user_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/kick_user.php',
        data:{user_id : user_id}, 
        success: function(){
            showConfirm("User kicked!");
        }
    })
}

function viewChat(user_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/change_to_userid.php',
        data:{user_id : user_id}, 
        success: function(){
            window.location.href = "../messages.php";
        }
    })
}

function emulateUser(user_id) {
    $.ajax({
        type: "POST",
        url: './scripts/display_profile/change_to_userid.php',
        data:{user_id : user_id}, 
        success: function(){
            window.location.href = "../hub.php";
        }
    })
}