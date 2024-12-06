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

// Function that searches for visits
function adminVisitSearch(search) {
    $.ajax({
        type: "POST",
        url: './scripts/visit_search.php',
        data:{ search : search }, 
        success: function(response){
            resultContainer = document.getElementById("visits-table");
            if (response == "none") {
                resultContainer.innerHTML = "<p class='no-records'>No results found containing '" + search + "'.</p>";
            } else {
                resultContainer.innerHTML = response;
            }
        }
    })
}

$('#message-container').delay(3500).fadeOut('slow');

// Shows a list of online players
function showOnlineList() {
    ajaxGet("./spa/dashboard_online_list.php", "dark-container");
}

// Filter out chats to match search
function adminChatSearch(search) {
    // Deletes none found element from previous query if it exists
    if ($('#none-found-p').length > 0) {
        document.getElementById('none-found-p').remove();
    }
    const resultContainer = document.getElementById('dashboard-chat-table');

    // Select all elements in result container
    const allElements = document.getElementById('dashboard-chat-table').children;

    // Convert to lower case to be case insensitive
    const searchLower = search.toLowerCase();

    // Hides all elements in result container
    Array.from(allElements).forEach(
        el => el.style.display = "none"
    )
    
    // Filter the elements based on the search
    const filteredElements = Array.from(allElements).filter(el =>
        el.id.toLowerCase().includes(searchLower)
    );

    // Checks if any nicknames with the search query were found
    if (filteredElements.length === 0) {
        // Not found
        // Create p tag that says that the search returned to results
        const para = document.createElement("p");
        para.id = "none-found-p";
        const node = document.createTextNode("No results found containing '" + search + "'.");

        // Put the p tag inside result container
        para.appendChild(node);
        resultContainer.appendChild(para);
    } else {
        // Found
        // Show the elements that have been filtered
        filteredElements.forEach(
            el => el.style.display = "table-row"
        )
    }
}

function adminSeeChat(tablename) {
    $.ajax({
        type: "POST",
        url: './scripts/set_seechat_tablename.php',
        data:{ tablename : tablename }, 
        success: function(){
            ajaxGet("./spa/see_chat.php", "dark-container");
        }
    })
}

setInterval(function(){
    ajaxGet("./scripts/load_online_count.php", "dashboard-player-online");
}, 2000)