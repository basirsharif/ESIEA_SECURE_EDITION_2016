# Reverse 150 - ELF 150

On ouvre le challenge dans gdb : gdb -q elf150
Si on tape disass main on remarque qu'il n'y a pas de symboles. Il faut donc retrouver le main. 
Si l'on tape **info file** on obtient le point d'entrée : 0x80483e0
On peut observer les premières lignes de l'éxecutable avec x/30i 0x80483e0
On remarque que __libc_start_main est appelé avec comme dernier argument 0x80485cb

On observe donc les instructions situées à cette adresse : x/30i 0x80485cb
Dans ces trente premières lignes on peut voir des appels à ptrace et puts, c'est donc probablement l'adresse du main. Pour ne pas avoir à refaire toute cette gymnastique on fixe un breakpoint à l'entrée du programme utile : b* 0x80485cb

On lance le programme. Une fois que le programme s'arrête on veut pouvoir observer le main dans sa totalité. On demande donc au débuggeur d'afficher un grand nombre d'instruction à partir de notre position actuelle : x/120i $pc

On peut remarquer, entre autres :
- Le programme appelle ptrace
- Le programme affiche quelque chose avec printf
- Le programme appelle une fonction sans nom
- Le programme appelle une autre fonction
- Selon le résultat de cette fonction, un message est affiché
- Le programme termine


A priori, le premier message est le message d'accueil. De même, on peut supposer que les messages
dépendant de la fonction inconnue #2 sont les messages de succès ou d'échec. On en déduit donc que la
fonction inconnue #2 est la fonction qui vérifie le mot de passe.
On ouvre la fonction pour se faire une idée de ce qui s'y passe : x/120i 0x8048518

On remarque une suite de comparaison à partir d'une même adresse :
0x65, 0x73, 0x65, 0x7b, 0x7d et une comparaison entre al et dl
En se référant à la table ascii, on obtient pour les premières comparaisons : ese{}. C'est donc bien la fonction que l'on recherche ! Mais avant d'y accéder il faut contourner ptrace. On est encore breaké à l'entrée du main, donc on affiche les premières lignes de main : x/30i $pc

On place un breakpoint sur le test qui suit ptrace : b* 0x80485ee

On en profite pour placer un breakpoint sur la fonction que l'on a commencé à analyser : 
b*0x8048518


On continue l'exécution du programme avec la commande : c
Le test porte sur le bit de signe, on change donc son état actuel.

> set $eflags ^= 1<<7


On peut donc continuer l'exécution. Le programme nous demande le mot de passe. On peut imaginer que le flag est de la forme ese{xxxxxx}, même si l'on ne connait pas encore la taille ni le contenu.


On entre donc un flag bidon, comme ese{aaaa} et on continue l'exécution. L'exécution s'arrête à l'entrée de la fonction, qu'on va afficher pour mieux comprendre ce qui se passe : x/60i $pc

On sait déjà que les premiers caractères sont bien "ese{". Les instrucions de type "add $0xn,%eax" en
sont la preuve (Où n est l'offset). Par contre, le test pour le caractère } n'est pas de la même forme. La
raison étant probablement que c'est le dernier caractère du flag.
Comme on ne connait pas la taille exacte du flag, il y a de fortes chances que ce test échoue et nous renvoie hors de la fonction. On va donc bypasser le test.

On place un breakpoint sur le jump : b* 0x804856f
Le jump est un jne, donc on set le flag zero à 1 : set $eflags |= 1<<6
On peut donc se concentrer sur le reste du flag. La comparaison qui nous intéresse est cmp %al, %dl

On regarde ce qui se passe juste après ce teste :
- Si les deux registres sont égaux on continue un peu plus loin
- Sinon, la fonction quitte en retournant 0
Devoir à chaque fois bypasser ce jump va vite devenir agaçant, donc on nop les instructions mov et jmp :

set *(char*)0x80485a2 = 0x90

set *(char*)0x80485a3 = 0x90

set *(char*)0x80485a4 = 0x90

set *(char*)0x80485a5 = 0x90

set *(char*)0x80485a6 = 0x90

set *(char*)0x80485a7 = 0x90

set *(char*)0x80485a8 = 0x90

Une fois que c'est fait, on place un breakpoint sur la comparaison : b* 0x804859e

Il ne reste plus qu'à récupèrer le flag :
- On continue jusqu'au breakpoint avec : c
- On affiche les registres : info reg
- On note le contenu de dl
- On recommence jusqu'à ce que le programme termine
En regardant dans la table ascii les valeurs successives de dl on obtient : nothathard

On recompose le flag avec ce que l'on savait avant, et victoire ! 
Le flag est : **ese{notthathard}**