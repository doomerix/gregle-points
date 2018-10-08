<?php
//      This file should be included in the pages
//      where you want to access the MySQL database.
$databaseName = "points";
$userName = "root";
$password = "walrus";
$address = "localhost";
$port = 3306;

$connection = new mysqli($address, $userName, $password, $databaseName, $port);
