// Function that searches for users
function adminUserSearch(search) {
    $.ajax({
        type: "POST",
        url: './scripts/admin_search.php',
        data:{ search : search }, 
        success: function(response){
            resultContainer = document.getElementById("user-search-results");
            if (response == "none") {
                resultContainer.innerHTML = "No user id, username or nickname found containing '" + search + "'.";
            } else {
                resultContainer.innerHTML = response;
            }
        }
    })
}

$('#message-container').delay(2000).fadeOut('slow');