<?php
    require 'libs/rb.php';
    $database = new PDO("mysql:host=localhost","root","");
    $database->query("CREATE DATABASE IF NOT EXISTS `fabeltjeskrant`");
    $database->query("use `fabeltjeskrant`");
    R::setup( 'mysql:host=localhost;dbname=fabeltjeskrant',
        'root', '' );
?>
