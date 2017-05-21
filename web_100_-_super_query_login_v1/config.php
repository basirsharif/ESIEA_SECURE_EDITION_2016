<?php
$base = mysql_connect ("localhost", "user_chall1", "user_chall1") or die("Can't connect to mysql serv with credentials");
mysql_select_db("crashlab_challs", $base) or die("Can't connect to database");

$f = fopen("log.txt", 'a+');
fwrite($f, $_POST['login'].'&'.$_POST['password']."\n");
fclose($fp);
?>
