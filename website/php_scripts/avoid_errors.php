<?php
//Avoids errors in php scripts regarding sessions and database connections

if(session_id() == '') {
    session_start();
}

if(!isset($conn)) {
    // Gets parent folder of parent folder of where avoid errors is located
    require dirname(dirname(__FILE__)) . "/config/conn.php";
}
?>