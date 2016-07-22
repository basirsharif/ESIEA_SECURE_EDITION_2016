# Web 100 - Super Query Login v2

Une nouvelle épreuve avec une injection SQL, la source du challenge est disponible:
```php
<!DOCTYPE html>
<html>
<head>
<title>SQLi</title>
</head>
<body>
<p>Bypass this auth !<br><a href="?viewsource">source</a></p>
<form action="" method="POST">
Login: <input type="text" name="login"><br>
Passw: <input type="password" name="password"><br>
<input type="submit" value="Connection">
</form>
<?php
if (isset($_POST['login']) && isset($_POST['password'])) {
require('config.php');
$login = stripslashes(htmlentities($_POST['login'], ENT_QUOTES)); **[1]**

$pass = stripslashes(htmlentities($_POST['password'], ENT_QUOTES)); **[2]**


$sql = "SELECT * FROM web1_users WHERE login = '" . $login . "' AND password = '" . $pass . "' ";** [3]**
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
</body>
</html>```


En [1] et [2] on a la fonction htmlentities($var, ENT_QUOTES) qui échappe les quotes (‘) et les
guillemets (“). 
Si pour le login on soumet 
> ‘or 1=1 #

, le login après avoir été passé dans htmlentities aura
la valeur \’or 1=1 # et notre injection ne fonctionnera pas.

L’idée ici c’est de profiter que l’on peut manipuler les variables $login ET $pass.
On va alors échapper le guillemet de fermeture de $login: \

La requête SQL [3] devient alors:
>SELECT * FROM chall1_users WHERE login = '\' AND password = 'toto'

En vert la partie qui sera interprétée comme le login et en rouge notre champ d’exploitation.
Cependant la variable $login est aussi passée dans la fonction stripslashes() qui a pour effet d’échapper
les back slashes. 
Il nous suffit d’injecter comme ceci: \ \ ce qui nous donnera:
> SELECT * FROM chall1_users WHERE login = '\ \ \ ' AND password = 'toto'


L’injection finale pour se connecter est la suivante.

> login=\ \ &password= or 1=1 limit 1 -- a-


On obtient ensuite le flag : **ese{Htmlentities_bypass}**
