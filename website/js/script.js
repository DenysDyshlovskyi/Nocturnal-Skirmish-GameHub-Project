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

// POST description to php with jQuery
function saveDescription() {
    var text = $('textarea#descriptionTextArea').val();
    console.log(text);
    $.ajax({
        type: "POST",
        url: "./php_scripts/save_description.php",
        data:{ description: text }, 
        success: function(data){
            console.log(data); 
        }
    })
}