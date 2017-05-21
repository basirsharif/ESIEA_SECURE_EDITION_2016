<?php
if(preg_match("/sqlmap/", $_SERVER['HTTP_USER_AGENT']))
	exit;
$pdo = new PDO('sqlite:'.dirname(__FILE__).'/1fd04627f2ce0425d838b52a86e9a9bc.sqlite');
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // ERRMODE_WARNING | ERRMODE_EXCEPTION | ERRMODE_SILENT
?>
