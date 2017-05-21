<?php
require 'config.php';

if (isset($_POST['username']) && isset($_POST['password'])) {

	$q = $DB->query("SELECT id, rank FROM web2_users WHERE username = '".$_POST['username']."' AND password = '".$_POST['password'] . "'");
	if ($d = $q->fetch()) {
		if ($d['rank'] == 1) {
			echo "Yess, you win: ese{st4cked_for_the_w1n!}";
		}else {
			echo "Welcome back ".htmlentities($_POST['username'] . " but you're simple user (rank=0)");
		}
	}
	else {
		echo "Wrong username/password";
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>Super Query Login v2.0</title>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link href="./style/index.css" rel="stylesheet" type="text/css">
		
	</head>
	<body>
		<h1>Super Query Login v2.0</h1>
		<div class="login-box">	
			<form id='login' name='login' method='POST' action='#'>			
				<div class="ribbon-wrapper h2 ribbon-red">
					<div class="ribbon-front">
						<h2>User Login</h2>
					</div>
					<div class="ribbon-edge-topleft2"></div>
					<div class="ribbon-edge-bottomleft"></div>
				</div>

				<div id='wrapper-box'> 
					<img id='login-image' src='images/user.png' >
					<input type=text id='login-name' name='username' placeholder='Username' >
				</div>
				<div id='wrapper-box'>
					<img id='login-image' src='images/lock.png' >
					<input type=password id='login-pass' name='password' placeholder='Password' >
				</div>				
				<input type=submit id='login-submit' value="Log in">
			</form>
		</div>
	</body>
</html>