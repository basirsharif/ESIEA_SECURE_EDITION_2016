<?php

function get_flag() {
	$pdo = new PDO('sqlite:'.dirname(__FILE__).'/f7f3e12a3e0b43d6421c9fc3d775058f.sqlite');
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$q = $pdo->query("SELECT flag FROM flag");
	$d = $q->fetch();
	return $d['flag'];
}

?>
