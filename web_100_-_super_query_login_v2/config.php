<?php

/*
	DB Connector
*/
$DB_HOST = "localhost";
$DB_NAME = "crashlab_challs";
$DB_USER = "user_chall2";
$DB_PASS = "user_chall2";

try {
	$DB = new PDO('mysql:host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
}catch( PDOException $ex) {
	echo "PDO Error: $ex";
	#die();
}

?>
