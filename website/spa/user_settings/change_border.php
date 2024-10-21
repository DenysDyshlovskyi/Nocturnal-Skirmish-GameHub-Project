<style>
    #changeborder-button {
        background-color: #FFCF8C;
    }
</style>
<style><?php include "./css/change-border.css" ?></style>
<h1 class="settings-headline">Change border</h1>
<div class="settings-change-border-container">
    <div class="settings-change-border-inventory-container">
        <p>Choose a border from the ones you've unlocked.</p>
        <div class="settings-change-border-inventory">
            <?php include "../../php_scripts/load_border_inventory.php" ?>
        </div>
    </div>
</div>