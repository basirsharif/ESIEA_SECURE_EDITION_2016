# WEB 50 - Much Serie Very Analyse


Un challenge très simple, en regardant la source disponible nous voyons qu’un cookie lang est désérialisé.

Cette désarialisation entraîne l’exécution de include($this->lang);.
En sélectionnant français comme langue pour le site, nous obtenons ce cookie:

```O%3A4%3A%22Lang%22%3A1%3A%7Bs%3A10%3A%22%00Lang%00lang%22%3Bs%3A6%3A%22fr.
php%22%3B%7D```

Il est encodé sous forme d’URL, en le décodant on obtient :
```O:4:'Lang':1:{s:10:'\ x00Lang\ x00lang';s:6:'fr.php';}```

Avec fr.php qui est le fichier à inclure et 6 la longueur du nom du fichier.
Nous voulons inclure flag.txt, il suffit de modifier les paramètres décris plus haut:

```O%3A4%3A%22Lang%22%3A1%3A%7Bs%3A10%3A%22%00Lang%00lang%22%3Bs%3A8%3A%22fla
g.txt%22%3B%7D```

Nous obtenons **ese{D4mn_PhP_Object}**.