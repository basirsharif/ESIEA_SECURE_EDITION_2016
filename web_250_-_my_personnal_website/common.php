<?php
foreach ($_GET as $key => $value) {
	$$key = $value;
}

if (isset($lang) && !empty($lang)) {
	if (file_exists("lang/$lang.php"))
		include "lang/$lang.php";
	else
		include "lang/en.php";	
} else {
	include "lang/en.php";
}

?>
