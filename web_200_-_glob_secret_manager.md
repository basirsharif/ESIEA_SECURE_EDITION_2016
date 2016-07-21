# Web 200 - Glob Secret Manager

Le challenge est une interface web permettant d’accéder à des fichiers
confidentiels, on peut cliquer sur le bouton “Enter” et les fichiers secret 1
et 2 apparaissent.
On peut les afficher en les accédant via l’URL :
- Secret 1 avec URL + ‘/secret/secret 1’
- Secret 2 avec URL + ‘/secret/secret 2’


L’un des secrets contient la string suivante “Confidential Secret are
hidden”.

Il doit donc y avoir des fichiers cachés récupérables.
Pour le moment cette méthode reste une impasse, il est nécessaire de trouver autre chose. 

On essaie de
changer les paramètres dans l’url pour se déplacer dans les dossiers.
>http://localhost/ESE%20WEB%20XX%20-%20Glob-Secrets-Manager/index.php?url=../secret

On reçoit le message d’erreur suivant, apparemment la valeur n’est pas gérée correctement.

>Fatal
error:
Uncaught
exception
'UnexpectedValueException'
with
message
'DirectoryIterator::__construct(../secret): 


On cherche sur Google les éléments suivants “DirectoryIterator/Glob/PHP” et on arrive sur une page de
la documentation PHP nous expliquant les wrappers.
data:// — Données (RFC 2397)
phar:// — Archive PHP
glob:// — Trouve des noms de fichiers correspondant à un masque donné
Le wrapper glob paraît très intéressant, l’exemple ci-dessous provient de la doc.


> $it = new DirectoryIterator("glob://ext/spl/examples/*.php");


On applique le wrapper dans notre url pour lister tous les fichiers de secret.

> http://localhost/ESE WEB XX - Glob-Secrets-Manager/index.php?url=glob://secret/*

Aucune erreur n’est affichée, nous sommes sur la bonne voie, on essaie ensuite de remonter
l'arborescence.
> http://localhost/ESE%20WEB%20XX%20-%20Glob-Secrets-Manager/index.php?url=glob://*

Le code source nous renvoie : "<p>Sorry, you must stay in the secret directory <!-- or not ?--"

Apparemment, il est possible de remonter mais il nous faut trouver un bypass.
Essayons de remonter en faisant croire au serveur que nous sommes dans secret à l'aide d'un null byte
> http://localhost/ESE WEB XX - Glob-Secrets-Manager/index.php?url=glob://*%00secret


Ça fonctionne, on obtient tous les fichiers mais rien de concluant.
On se souvient du contenu du fichier secret 1 : Confidential Secret are hidden. On ré-essaie donc
mais en affichant les fichiers cachés comme un ls -a.

>http://localhost/ESE WEB XX - Glob-Secrets-Manager/index.php?url=glob://.*%00secret

.**ese{f1lter_4ll_th3_th1ngs}**

