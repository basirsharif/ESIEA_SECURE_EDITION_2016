<?php

require 'functions.php';
//admin page

if (isset($_SESSION['auth']) && $_SESSION['auth'] == "true") {
	echo get_flag();
}
else if (isset($_POST['password']) && sha1($_POST['password']) == "cdbd845f59a4350228398a5d47689e2077bb14f2") {
	$_SESSION['auth'] = 'true';
	header('Location: admin.php');
}
else {
	echo "<form action='' method='POST'>";
	echo "Password <input type='password' name='password' ><br>";
	echo "<input type='submit' value='Login !'>";
	echo "</form>";
}

?>
