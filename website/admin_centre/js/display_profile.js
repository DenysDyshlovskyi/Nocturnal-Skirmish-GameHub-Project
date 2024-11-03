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
                borderContainer.innerHTML = "No borders.";
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