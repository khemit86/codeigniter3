<?php

/*
 * Simple script to create necessary tables for running this app
 * Please ensure that the database parameters in ../app/bootstrap.php
 * are correct and that the necessary db privileges have been granted
 */

echo "Creating mysql tables" . PHP_EOL;
if(!extension_loaded('mysql')) {
	echo "Please enable the mysql extension to run this app" . PHP_EOL;
	exit(1);
}

require_once __DIR__ . '/../app/bootstrap.php';


$link = mysqli_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD,MYSQL_DB);
if(!$link) {
	 echo 'Could not connect to mysql ' . mysqli_error() . PHP_EOL;
	 exit(1);
}

$sql = @file_get_contents(__DIR__ . "/db.sql");
$statements = explode(';', $sql);
foreach($statements as $query) {
	if($query) {
		echo "Executing statement:" .  $query . PHP_EOL;
		mysqli_query($link,$query) or die(mysqli_error());
	}
}
echo PHP_EOL . "Database creation complete" . PHP_EOL;
mysqli_close($link);