<?php
//Get 24-hour time format in Oslo
$datetime = new DateTime( "now", new DateTimeZone( "Europe/Oslo" ) );
$date = $datetime->format( 'Y-m-d' );
$time = $datetime->format( 'H:i' );
?>