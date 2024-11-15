<?php
// Adds 25 to the loaded amount of messages
session_start();
$_SESSION['message_amount'] = $_SESSION['message_amount'] + 25;