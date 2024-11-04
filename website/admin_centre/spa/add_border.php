<?php
session_start();
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
};
?>
<style>
    #dark-container {
        display: block;
    }
</style>
<style><?php include "../css/add_border.css" ?></style>
<div class="border-add-container">
    <h1>Add border to uID <?php echo $_SESSION['displayprofile_userid'] ?>'s inventory.</h1>
    <div class="table_container">
        <table>
            <tr>
                <th>Picture</th>
                <th>Name</th>
                <th>Add</th>
            </tr>
            <?php include "../scripts/display_profile/load_add_borders.php" ?>
        </table>
    </div>
    <button onclick="removeDarkContainer()" id="cancel-button">Cancel</button>
</div>