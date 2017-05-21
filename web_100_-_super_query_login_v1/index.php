<?php 
if (isset($_GET['viewsource'])) {
	highlight_file('index.php');
	exit;
}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Super Query Login v1.0</title>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link href="./style/index.css" rel="stylesheet" type="text/css">
	</head>
	<body>
		<h1>Super Query Login v1.0</h1>

		<div class="login-box">	
			<form id='login'  method='POST'>			
				<div class="ribbon-wrapper h2 ribbon-red">
					<div class="ribbon-front">
						<h2>User Login</h2>
					</div>
					<div class="ribbon-edge-topleft2"></div>
					<div class="ribbon-edge-bottomleft"></div>
				</div>

				<div id='wrapper-box'> 
					<img id='login-image' src='images/user.png' >
					<input type=text id='login-name' name='login' placeholder='Username' >
				</div>
				<div id='wrapper-box'>
					<img id='login-image' src='images/lock.png' >
					<input type=password id='login-pass' name='password' placeholder='Password' >
				</div>	
				<p>Bypass this auth !<br><a href="?viewsource">source</a></p>			
				<input type=submit id='login-submit' value="Log in">
			
			</form>
			<?php
			if (isset($_POST['login']) && isset($_POST['password'])) {
				require('config.php');
				$login = stripslashes(htmlentities($_POST['login'], ENT_QUOTES));
				$pass = stripslashes(htmlentities($_POST['password'], ENT_QUOTES));
				
				$sql = "SELECT * FROM web1_users WHERE login = '" . $login . "' AND password = '" . $pass . "' ";
				$result = mysql_query($sql) or die(mysql_error());
				if( mysql_num_rows($result) == 1) {
					$data = mysql_fetch_array($result);
					echo "You can valid with <b>".$data['password']."</b>";

				}
				else {
					echo "<b>Wrong login/password !</b>";
				}
			}
			?>
		</div>
	</body>
</html>