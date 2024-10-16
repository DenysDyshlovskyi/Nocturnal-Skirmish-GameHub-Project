function openShopTab(evt, tabName) {
    var i, tabContent, shopLink;

    // Hide all tab content
    tabContent = document.getElementsByClassName("tabContent");
    for (i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = "none";
    }

    // Remove "active" class from all shop links
    shopLink = document.getElementsByClassName("shopLink");
    for (i = 0; i < shopLink.length; i++) {
        shopLink[i].className = shopLink[i].className.replace(" active", "");
    }

    // Show the clicked tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

// Show the featured tab by default when the page loads
document.addEventListener("DOMContentLoaded", function() {
    const featuredButton = document.querySelector('.shopLink'); // Select the first button
    openShopTab({ currentTarget: featuredButton }, 'featured');
});
