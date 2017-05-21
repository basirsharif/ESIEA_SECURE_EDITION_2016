# Misc 50 - LOLCODE

Le LOLCODE est un langage de programmation assez exotique, la page Wikipédia
(https://fr.wikipedia.org/wiki/LOLCODE) est notre meilleure amie pour nous aider à comprendre ce
challenge.

Détaillons un peu le “Hello World”.
>HAI -> Lance le programme
>
>CAN HAS STDIO? -> Import STDIO
>
>BTW affiche "Hello world!" à l'écran -> Commentaire

>VISIBLE "Hello world!" -> Affiche “Hello World!” à l’écran
>
>KTHXBYE -> Quitte le programme

L’opérateur VISIBLE correspond à la fonction PRINT, on essaie donc d’afficher le contenu de la variable
FLAG.


> swissky@crashlab:~$ echo "VISIBLE FLAG" | nc 90.2.131.77 4242

```lolcode
HAI
CAN HAS STDIO?
IM IN YR 2016
BTW ESE IZ FUN
ese{much_lolc0de_so_flag}
IM OUTTA YR 2016
KTHXBYE```


On récupère ainsi le flag **ese{much_lolc0de_so_flag}**