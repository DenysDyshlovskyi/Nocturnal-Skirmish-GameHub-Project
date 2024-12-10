<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameHub & Nocturnal Skirmish - Tutorial</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/tutorial.css" ?> </style>
</head>
<body>
    <div class="tutorial-sidebar">
        <div class="tutorial-sidebar-headline">GameHub</div>
        <button id="getting-started" title="Getting started" onclick="ajaxGet('./spa/tutorial/getting-started.php', 'tutorial-article-container', 'no_sfx');">1 - Getting started</button>
        <button id="customizing" title="Customizing your profile" onclick="ajaxGet('./spa/tutorial/customizing.php', 'tutorial-article-container', 'no_sfx');">2 - Customizing your profile</button>
        <button id="choosing_settings" title="Choosing your settings" onclick="ajaxGet('./spa/tutorial/choosing_settings.php', 'tutorial-article-container', 'no_sfx');">2.1 - Choosing your settings</button>
        <button id="adding_friends" title="Adding Friends" onclick="ajaxGet('./spa/tutorial/adding_friends.php', 'tutorial-article-container', 'no_sfx');">3 - Adding Friends</button>
        <button id="accepting_friends" title="Accepting pending friend requests" onclick="ajaxGet('./spa/tutorial/accepting_friends.php', 'tutorial-article-container', 'no_sfx');">3.1 - Accepting pending friend requests</button>
        <button id="removing_friends" title="Removing friends" onclick="ajaxGet('./spa/tutorial/removing_friends.php', 'tutorial-article-container', 'no_sfx');">3.2 - Removing friends</button>
        <button title="Creating chats">4 - Creating chats</button>
        <button title="Creating groupchats">4.1 - Creating groupchats</button>
        <button title="Customizing groupchats">4.2 - Customizing groupchats</button>
        <button title="Adding friends to groupchat">4.3 - Adding friends to groupchat</button>
        <button title="Using the public chat">4.4 - Using the public chat</button>
        <button title="Navigating the shop">5 - Navigating the shop</button>
        <button title="Redeeming codes & socials">6 - Redeeming codes & socials</button>
        <button title="What to do next">7 - What to do next</button>
    </div>
    <div id="tutorial-article-container" class="tutorial-article-container">

    </div>
    <?php
    if (isset($_GET['origin'])) {
        if ($_GET['origin'] == "login") {
            echo "<button class='back-button' title='Back to login' onclick='tutorialBack(0)'>Back to login</button>";
        } else if ($_GET['origin'] == "hub") {
            echo "<button class='back-button' title='Back to hub' onclick='tutorialBack(1)'>Back to hub</button>";
        }
    } else {
        echo "<button class='back-button' title='To login' onclick='tutorialBack(0)'>To login</button>";
    }
    ?>
</body>
<script><?php include "./js/script.js" ?></script>
<script>
    // Changes redirect of back button
    function tutorialBack(mode) {
        if (mode == 0) {
            window.location.href = "index.php";
        } else if (mode == 1) {
            window.location.href = "hub.php";
        }
    }

    // If the section variable in url is gamehub, show getting started tab
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    if (urlParams.get('section')) {
        const section = urlParams.get('section');
        if (section == "gamehub") {
            ajaxGet("./spa/tutorial/getting-started.php", "tutorial-article-container", "no_sfx");
        }
    } else {
        ajaxGet("./spa/tutorial/getting-started.php", "tutorial-article-container", "no_sfx");
    }
</script>
</html>