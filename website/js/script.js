//Prevents form resubmission on refresh of page
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
};

//Function to switch visibility of dark container
function showDarkContainer() {
    container = document.getElementById("loginDarkContainer");
    if (window.getComputedStyle(container).display === 'none'){
        container.style.display = 'block';
    } else {
        container.style.display = "none";
    }
}

//Function to switch visibility of new password container
function showNewPassword() {
    event.preventDefault();
    container = document.getElementById("login-new-password-container");
    buttonContainer = document.getElementById("login-recovery-button-container-hide");

    if (window.getComputedStyle(container).display === 'none'){
        container.style.display = "block";
        buttonContainer.style.display = "none";
    } else {
        container.style.display = "none";
        buttonContainer.style.display = "block";
    }
}

// GET request with ajax
function ajaxGet(phpFile, changeID, onLoad){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        document.getElementById(changeID).innerHTML = this.responseText;
        prepareSFX();
        if (onLoad == "cropper_js") {
            configureCropperJS();
        }
    }
    xhttp.open("GET", phpFile);
    xhttp.send();
}

// Save description in database
function saveDescription() {
    var text = $('#descriptionTextArea').val();
    $.ajax({
        type: "POST",
        url: './php_scripts/save_description.php',
        data:{ description: text }, 
        success: function(){
            settingsShowConfirm("Description saved!")
        }
    })
}

function submitDevCode() {
    var text = $('#devcodeInput').val();
    $.ajax({
        type: "POST",
        url: './php_scripts/submit_devcode.php',
        data:{ devcode: text }, 
        success: function(response){
            if (response == "codenotfound") {
                settingsShowConfirm("Code doesn't exist or is expired.")
            } else if (response == "alreadyredeemed") {
                settingsShowConfirm("Code already redeemed!")
            } else {
                settingsShowConfirm("Code redeemed!")
            }
        }
    })
}

// Save new nickname in database
function saveNickname () {
    var text = $('#change-nickname-input').val();
    $.ajax({
        type: "POST",
        url: './php_scripts/save_nickname.php',
        data:{ nickname: text }, 
        success: function(nickname){
            if (nickname == "error") {
                settingsShowConfirm("Something went wrong.");
                removeDarkContainer();
            } else {
                document.getElementById("settings-myaccount-nickname").innerHTML = nickname;
                document.getElementById("settings-myaccount-details-nickname").innerHTML = nickname;
                removeDarkContainer();
                settingsShowConfirm("Description saved!");
            }
        }
    })
}

// Save new email in database
function saveEmail() {
    var text = $('#change-email-input').val();
    $.ajax({
        type: "POST",
        url: './php_scripts/save_email.php',
        data:{ email: text }, 
        success: function(email){
            if (email == "error") {
                settingsShowConfirm("Something went wrong.");
                removeDarkContainer();
            } else {
                document.getElementById("settings-myaccount-details-email").innerHTML = email;
                removeDarkContainer();
                settingsShowConfirm("Email saved!");
            }
        }
    })
}

// Uploads banner to server
function uploadBanner() {
    var file_data = $('#banner-input').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);                           
    $.ajax({
        url: './php_scripts/banner_image_upload.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(response){
            if (response == "unsupported") {
                settingsShowConfirm("File type not supported! Only JPG allowed.");
            } else if (response == "empty") {
                settingsShowConfirm("File input empty!");
            } else if (response == "error") {
                settingsShowConfirm("Something went wrong.");
            } else {
                settingsShowConfirm("Banner saved!");
                document.getElementById('settings-myaccount-banner').style.backgroundImage = response;
            }
            removeDarkContainer();
        }
    });
};

// Uploads temp profile pic to server
function uploadProfilePic() {
    var file_data = $('#profilepic-input').prop('files')[0];   
    var form_data = new FormData();                  
    form_data.append('file', file_data);                           
    $.ajax({
        url: './php_scripts/profilepic_upload.php',
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,                         
        type: 'post',
        success: function(response){
            if (response == "unsupported") {
                removeDarkContainer();
                settingsShowConfirm("File type not supported! Only JPG and PNG allowed.");
            } else if (response == "empty") {
                removeDarkContainer();
                settingsShowConfirm("File input empty!");
            } else if (response == "error") {
                removeDarkContainer();
                settingsShowConfirm("Something went wrong.");
            } else {
                container = document.getElementById("settings-dark-container");
                container.innerHTML = "";
                ajaxGet('./spa/user_settings/profilepic_crop.php', 'settings-dark-container', 'cropper_js');
            }
        }
    });
};

// Configures settings fro cropper js
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
					url:'./php_scripts/profilepic_cropped_upload.php',
					method:'POST',
					data:{image:base64data},
					success:function(response){
                        if (response == "error") {
                            settingsShowConfirm("Something went wrong.");
                            removeDarkContainer();
                        } else {
                            document.getElementById("settings-myaccount-profile-pic-parent").style.backgroundImage = response;
                            settingsShowConfirm("Profile picture saved!");
                            removeDarkContainer();
                        }
					}
				});
			};
		});
    });
};

//Shows a popup when saving information in user settings
function settingsShowConfirm(text) {
    confirmContainer = document.getElementById("confirmContainer");
    confirmContainer.innerHTML = text;
    confirmContainer.style.display = "block";
    $("#confirmContainer").fadeOut(3000);
}

// Shows preview of banner that user is uploading
function filePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#bannerPreview').css('background-image','url('+e.target.result+')');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

//Removes dark container and its contents
function removeDarkContainer() {
    container = document.getElementById("settings-dark-container");
    if (window.getComputedStyle(container).display != 'none') {
        document.getElementById("settings-dark-container").innerHTML = "";
        document.getElementById("settings-dark-container").style.display = 'none';
    }
}

//Prepares sound effects for a page
function prepareSFX() {
    var hoverAudio = document.getElementById('hoverSFX');
    // Function to play hover soundeffect on button hover
    function playHoverSfx() {
        hoverAudio.play();
    }

    // Function to stop sfx hover
    function stopHoverSfx() {
        hoverAudio.pause();
        hoverAudio.currentTime = 0;
    }

    // Get all button and a tags
    const hoverSfxButton = document.getElementsByTagName('button');
    const hoverSfxLink = document.getElementsByTagName('a');

    //Loops through all button tags and adds a mouse over event listener
    for (var i = 0 ; i < hoverSfxButton.length; i++) {
        hoverSfxButton[i].addEventListener('mouseover', () => {playHoverSfx();});
        hoverSfxButton[i].addEventListener('mouseout', () => {stopHoverSfx();});
    }

    //Loops through all link (a) tags and adds a mouse over event listener
    for (var i = 0 ; i < hoverSfxLink.length; i++) {
        hoverSfxLink[i].addEventListener('mouseover', () => {playHoverSfx();});
        hoverSfxLink[i].addEventListener('mouseout', () => {stopHoverSfx();});
    }
}

// play click sfx
function playClickSfx() {
    var clickAudio = document.getElementById('clickSFX');
    clickAudio.play();
}

// Stop click sfx
function stopClickSfx() {
    var clickAudio = document.getElementById('clickSFX');
    clickAudio.pause();
    clickAudio.currentTime = 0;
}

// Click sfx on whole document
const clickSfxBody = document.querySelector('body');
clickSfxBody.addEventListener('click', () => {
    stopClickSfx();
    playClickSfx();
});