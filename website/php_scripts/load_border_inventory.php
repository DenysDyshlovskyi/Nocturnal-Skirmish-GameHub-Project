<?php
require "avoid_errors.php";
// Loads in borders from users inventory and displays them

//Get borders from database
$sql = "SELECT * FROM border_inventory WHERE user_id = " . $_SESSION['user_id'];
$result = $conn->query($sql);
if ((mysqli_num_rows($result) <= 0)) {
    echo 
    "<div class='settings-change-border-noborders'>
        <h1>Looks like you dont have any borders unlocked!</h1>
        <p>Want new borders?</p>
        <button>Border Shop</button>
        <br>
        <button>FaQ</button>
    </div>";
} else {
    // For each border in users inventory
    while ($row = mysqli_fetch_assoc($result)) {
        printf( 
        "<button onclick='saveBorder(%s)' class='settings-change-border-inventory-border' id='" . $row['border'] . "' style='background-image: url(" . $_SESSION['user_profile_picture'] . ");'>
            <img src='" . "./img/borders/" . $row['border'] . "'>
        </button>", '"' . $row['border'] . '"');
    }
}
?>