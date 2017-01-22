<?php
require 'DBProvider.php';

$dbName = 'testtask';
$userName = 'postgres';
$password = '';

$messageLimit = 25;

$dbprovider = new DBProvider($dbName, $userName, $password);