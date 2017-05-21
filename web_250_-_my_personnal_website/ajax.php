<?php 

if (isset($_GET['load']) && !empty($_GET['load'])) {
	if (preg_match("/^([a-z]+$)/i", $_GET['load']))	
		echo file_get_contents($_GET['load'].'.php');
}


?>
