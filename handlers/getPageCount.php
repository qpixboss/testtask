<?php
require $_SERVER['DOCUMENT_ROOT'] . '/config.php';

$rows = $dbprovider->getRowCount();

echo ceil($rows/$messageLimit);