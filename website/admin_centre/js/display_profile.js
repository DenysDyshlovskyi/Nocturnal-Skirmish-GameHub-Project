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

//Shows a popup when saving information in user settings
function showConfirm(text) {
    $("#confirmContainer").stop( true, true ).fadeOut();
    confirmContainer = document.getElementById("confirmContainer");
    confirmContainer.innerHTML = text;
    confirmContainer.style.display = "block";
    $("#confirmContainer").fadeOut(3000);
}