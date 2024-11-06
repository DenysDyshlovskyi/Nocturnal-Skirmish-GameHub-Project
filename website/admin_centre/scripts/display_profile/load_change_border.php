<?php
require dirname(dirname(dirname(__DIR__))) . "/php_scripts/avoid_errors.php";
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../../admin_login.php?error=unauth");
} else {
    // For each border in borders directory, add it to table
    $user_id = $_SESSION['displayprofile_userid'];

    $borders = array_diff(scandir(dirname(dirname(dirname(__DIR__))) . "/img/borders"), array('..', '.'));
    foreach($borders as $file) {
        printf("
        <tr>
            <td>
                <img src='../img/borders/$file'>
            </td>
            <td>
                $file
            </td>
            <td>
                <button onclick='changeBorder($user_id, %s)'>Change to this border</button>
            </td>
        </tr>
        ", '"' . $file . '"');
    };
}
