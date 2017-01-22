<?php
require 'DBProvider.php';

$dbName = 'testtask';
$userName = 'postgres';
$password = '';

$messageLimit = 5;

$dbprovider = new DBProvider($dbName, $userName, $password);