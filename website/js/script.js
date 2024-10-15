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
function ajaxGet(phpFile, changeID){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        document.getElementById(changeID).innerHTML = this.responseText
    }
    xhttp.open("GET", phpFile);
    xhttp.send();
}

// POST request with jQuery ajax
function ajaxPost(postText, phpFile, confirm) {
    var text = $(postText).val();
    $.ajax({
        type: "POST",
        url: phpFile,
        data:{ description: text }, 
        success: function(){
            settingsShowConfirm(confirm)
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
    document.getElementById("settings-dark-container").innerHTML = "";
    document.getElementById("settings-dark-container").style.display = "none";
}