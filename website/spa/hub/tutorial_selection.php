<style> <?php include "./css/tutorial-selection.css" ?> </style>
<div class="selection-container">
    <div class="selection-headline-relative">
        <div class="selection-headline-relative-inner">
            <h1>Tutorial: Selection</h1>
        </div>
    </div>
    <div class="selection-container-inner">
        <button class="selection-gamehub" title="GameHub: Beginners Tutorial" onclick="window.location.href='tutorial.php?section=gamehub&origin=hub'">
            <div class="selection-text-box">
                <p>GameHub:<br>Beginners Tutorial</p>
            </div>
            <img src="./img/Noc_Skir_Logo.svg">
        </button>
        <button title="Nocturnal Skirmish: How to battle" class="selection-nocturnal">
            <div class="selection-text-box">
                <p>Nocturnal Skirmish:<br>How to battle</p>
            </div>
        </button>
    </div>
    <div class="go-back-container">
        <button onclick="removeDarkContainer()" title="Go back to Hub.">Go Back</button>
    </div>
</div>