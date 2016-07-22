# Web 50 - FlagShop

De nombreux participants pensaient acheter des flags pour monter plus vite dans le classement, mais ils
ont vite été ralenti par l’authentification requise dans ce challenge. Le code SQL ci-dessous contient la
requête effectuée pour les authentifier.
```php
$req = $DB->prepare("SELECT * FROM users WHERE (username=:username AND password='".str_replace(';', '[REMOVED]',$_POST['password'])."')");
$req->execute(array("username" => $_POST['username'] ));```



On peut donc injecter dans le champ “password”, on tente une SQLi avec le payload : **') or 1=1#**

Détaillons un peu le payload caractère par caractère.

> **'** : permet de fermer le selecteur password=’’
 
> **)** : permet de fermer la requête select * from users where (username ...)

> **Or** : permet d’exprimer une autre condition car “false” or “true” renvoie “true”

> **1=1** : permet d’obtenir une requête toujours vraie select * where (username=xxx) or 1=1

> **\#** : débute un commentaire, le reste de la requête est ignorée select * where (username=xxx) or 1=1#bla


Grâce à ce payload on accède au reste du site en étant connecté en tant que user1 , malheureusement
son compte en banque est insuffisant pour acheter mais on peut envoyer de l’argent à un ami. Essayons
de nous connecter avec différents utilisateurs (ou on peut utiliser SQLMap et regarder le compte de
chacun d’entre eux). Le payload pour se connecter en tant que user2 est le suivant :


> ') or 1=1 limit 1,2# 


On accède à un formulaire nous permettant de transférer de l’argent.


Afin d’éviter que les participants du CTF ne vide tous les comptes des users, ceux-ci ne possèdent pas
assez pour acheter un flag. Il devient assez évident qu’il faut trouver un autre moyen, il n’est
malheureusement pas possible de faire une STACKED QUERY pour ajouter un autre utilisateur dans la
base de données à l’aide d’un INSERT INTO.
En jouant un peu avec le site on obtient la requête suivante lorsque l’on envoie de l’argent à un ami.


> http://localhost/ESE%20WEB%20X%20-%20FlagShop/index.php?do=displayMoney&to=user2



Deux paramètres nous interpellent, displayMoney pourrait être une fonction ou une page et user2
correspond au nom d’utilisateur recevant l’argent. On tente d’afficher un phpinfo, il fonctionnerait en
local mais nous l’avons bloqué dans ce challenge pour éviter d’afficher des informations sur la
configuration du serveur. En revanche on peut essayer un die afin de stopper le flux d’exécution de PHP.


```php
if(isset($_GET['do']) && isset($_GET['to'])){
  if(giveMoney($_GET['to']) === true){

  # Display XXX money envoyé or execute a single function ?
  $do = $_GET["do"];

  $do();
  # Remove money
  removeMoney();
  }
}```


La fonction die() est exécutée grâce à la magie de PHP, notons qu’une fonction inexistante telle que
whatever() aurait aussi interrompu le flux d’exécution de PHP en soulevant une erreur. Cette
interruption empêche ainsi le retrait d’argent du compte, il suffisait de spammer F5 pour remplir son
compte et ensuite acheter le flag : **ese{c0ntrol_the_3l3ments_1s_th3_k3y}**