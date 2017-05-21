# Reverse 100 - ELF 100

On ouvre le challenge dans gdb : gdb -q elf100
On désassemble le main : 
>disass main 

En observant les jumps dans le code, on remarque qu'un test est effectué juste après l'affichage du message d'accueil, et que le jump nous renvoie tout en bas du main, peu avant le return.

On break donc sur ce saut.
>b* main+44

On toggle le flag zero (6 ème bit d'eflags en partant de la droite) pour empêcher le programme de prendre le saut.

>set $eflags ^= 1<<6

Victoire, le programme affiche le flag : **ese{89uG9ksrnoV}**