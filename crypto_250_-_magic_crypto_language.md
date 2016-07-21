# Crypto 250 - Magic Crypto Language

Le challenge tire parti du PHP Juggling Type et des Magic Hashes de PHP, c’est une authentification
basique que nous devons contourner pour accéder au flag.
```
if(isset($_POST['user']) && isset($_POST['pass'])){
$hash = hash('ripemd160', $_POST['pass']);
$hash = substr($hash, 1,10);
$hash = $hash.$_POST['user'];
}
if($hash == $secret_token){
echo $secret_msg;
die();
}```

On cherche à obtenir un string representant un float en PHP à partir du hash, nous prendrons un magic
hash provenant du site “https://www.whitehatsec.com/blog/magic-hashes/”. Pour le hashage de type
RIPEMD160, c’est le nombre **20583002034**.
Le hash devient **00e1839085851394356611454660337505469745**. Il doit commencer par 2 zéros
pour passer la ligne “$hash = substr($hash, 1,10);”. Notre entrée est ensuite réduite à 0e1839085
et restera un float lorsqu’elle est concaténée avec le nom de l’utilisateur si et seulement si celui-ci est un
nombre.

On aura un hash de la forme 0e1839085[0-9]* qui est toujours la représentation d’un float.
On peut valider le challenge avec les identifiants suivant :

>Username: 0
>
>Password: 20583002034

Un fois connecté, le flag apparait **ESE{H4cking_B0wling_and_Juggl1ng}**