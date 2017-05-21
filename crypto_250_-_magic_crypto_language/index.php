<!DOCTYPE html>
<html>
	<head>		
		<meta charset="UTF-8" />
		<title>ESE - Magic Crypto</title>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
		<link href="./style/index.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="js/login.js"></script>
	</head>
	<body>
		<h1>ESE - Magic Crypto</h1>
		<div class="login-box">	
		<?php
			include('secret.php');

			if(isset($_GET['showfile'])){
				highlight_file(__FILE__);
				die();
			}

			if(isset($_POST['user']) && isset($_POST['pass'])){
				
				# Sanitize Password
				$hash = hash('ripemd160', $_POST['pass']);
				$hash = substr($hash, 1,10);
				$hash = $hash.$_POST['user'];

				# Sanitize 
				if($hash == $secret_token){
					echo $secret_msg;
					die();
				}
				else{
					echo $secret_fail;
				}
			}
		?>
			<form  id='login' name='login' method='POST'>
				<div class="ribbon-wrapper h2 ribbon-red">
					<div class="ribbon-front">
						<h2>User Login</h2>
					</div>
					<div class="ribbon-edge-topleft2"></div>
					<div class="ribbon-edge-bottomleft"></div>
				</div>

				<div id='wrapper-box'> 
					<img id='login-image' src='images/user.png' >
					<input type=text id='login-name' name='user' placeholder='Username' >
				</div>
				<div id='wrapper-box'>
					<img id='login-image' src='images/lock.png' >
					<input type=password id='login-pass' name='pass' placeholder='Password' >
				</div>		
				<a href='?showfile'>Source</a>		
				<input type=submit id='login-submit' value="Log in">
			</form>
		</div>
	</body>
</html>