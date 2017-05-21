<?php


if(isset($_GET['viewsource'])) {
	highlight_file('index.php');
	exit;
}
/****

CREATE TABLE users (
    id       INT,
    login    CHAR,
    password CHAR
);


CREATE TABLE admin_list (
    id_user INT,
    email   CHAR
);

****/
require 'config.php';

function getInfoUser($login) {
	global $pdo;
	$q = "select id from users where login = '". $login ."'";
	try {
		$q = $pdo->query($q) or die("SQL Error [1]");
		$r = $q->fetch();
		if(!$r) $r['id'] = 1;
		return $r['id'];
	}catch(Exception $e) {
		echo "SQL Error: ".$e->getMessage();
	}
	return 1;
}


function userInList($id) {
	global $pdo;
	$q = "select email from admin_list where id_user = $id";
	try {
		$q = $pdo->query($q);
		$r = $q->fetch();
		return $r['email'];
	}catch(Exception $e) {
		echo "SQL Error: ".$e->getMessage();
	}
	return ;
}
?>


<!DOCTYPE html>
<html>
<head>
<title>La route est longue !</title>
<link href="css/style.css" rel='stylesheet' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
</head>
	<body>

	<div class="main">
		<h1>La route est longue !</h1>
		<div class="invite-01">
			<div class="invite-head">
				<h2>Search Box <span></span></h2>
			</div>
			<div class="one-invite">				
				<form action='' method='get'>
					<input type="password" name="login" placeholder="Enter a text" required/>
					<div class="submit">
						<input type="submit" value="Search" >
					</div>

				</form>
				
				<?php
				if(isset($_GET['login'])) {
					echo "<br><strong>Informations</strong>";
					$id_user = getInfoUser($_GET['login']);
					$email = userInList($id_user);
					echo "<p>Login: ".htmlentities($_GET['login'])."<br>Email: ".htmlentities($email)."</p>";
				}
				?>
				<p>
					<a href="?viewsource">Source</a>
				</p>
			</div>
		</div>
	</div>
	</body>
</html>

