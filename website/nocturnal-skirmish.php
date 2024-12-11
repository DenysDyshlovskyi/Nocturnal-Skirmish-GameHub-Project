<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nocturnal Skirmish - Main Menu</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/nocskir-mainmenu.css" ?> </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Silkscreen:wght@400;700&display=swap" rel="stylesheet">
</head>
<body id="nocskir-body" onload="prepareSFX();">
<div id="dark-container" class="dark-container"></div>
    <div class="nocskir-slideshow">
        <div class="nocskir-slideshow-items">
            <img src="./img/cards/AncientSpirit_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/BlackCat_Card_tailwag.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Bloodmoon_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/CryoEruption_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Deep_Ocean_Dweller_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/DivergentSpirit_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/PressureBlast.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/BloomingRejuvenation_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/SandsOfTime_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/FlameBarrier_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Whale_Symphony_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/ThrowingDaggers_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/SpiritRelease_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Soul_Scythe.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Punch_card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Cleave_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/IceMirror_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Blight_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/IceShield_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/DivineBlade_Card.webp" class="nocskir-slideshow-img">
        </div>
        <div class="nocskir-slideshow-items">
            <img src="./img/cards/AncientSpirit_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/BlackCat_Card_tailwag.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Bloodmoon_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/CryoEruption_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Deep_Ocean_Dweller_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/DivergentSpirit_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/PressureBlast.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/BloomingRejuvenation_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/SandsOfTime_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/FlameBarrier_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Whale_Symphony_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/ThrowingDaggers_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/SpiritRelease_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Soul_Scythe.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Punch_card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Cleave_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/IceMirror_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/Blight_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/IceShield_Card.webp" class="nocskir-slideshow-img">
            <img src="./img/cards/DivineBlade_Card.webp" class="nocskir-slideshow-img">
        </div>
    </div>
    <div class="nocskir-slideshow-gradient">
        <img src="./img/Noc_Skir_Logo.svg" class="nocskir-center-logo" draggable="false">
    </div>

    <div class="neon-button-container">
        <div>
        <a href="#" class="neon-button" onclick="ajaxGet('./spa/nocturnal-skirmish/gamemode_selection.php', 'dark-container')">Play</a>
        </div>
        <div>
        <a href="#" class="neon-button">Inventory</a>
        </div>
        <div>
        <a href="#" class="neon-button">Options</a>
        </div>
    </div>

    <div class="nocskir-buttons-container">
        <button class="nocskir-backtohub-button" onclick="window.location.href = 'hub.php'" title="Back to Hub">Back to Hub</button>
    </div>
</body>
<script><?php include "./js/script.js" ?></script>
<!-- Autolooping audio background music (works only if user allows it) -->
<audio autoplay loop style="display: none;" id="musicAudio">
    <source src="./audio/music/IntermissionOST.mp3" type="audio/mpeg">
</audio>
<!-- hover audio temp -->
<audio id='hoverSFX'>
        <source src="audio/sfx/hover.mp3" type="audio/mpeg">
    </audio>
    <!-- click sfx temp -->
    <audio id='clickSFX'>
        <source src="audio/sfx/click1.mp3" type="audio/mpeg">
    </audio>
</html>