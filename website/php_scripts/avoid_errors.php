<?php
//Avoids errors in php scripts regarding sessions and database connections

if(session_id() == '') {
    session_start();
}

if(!isset($conn)) {
    require dirname(dirname(__FILE__)) . "/config/conn.php";
}
?>