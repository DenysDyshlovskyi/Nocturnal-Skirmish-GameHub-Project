<?php
// Logs out the user
session_start();
session_unset();
header("Location: ../index.php")
?>