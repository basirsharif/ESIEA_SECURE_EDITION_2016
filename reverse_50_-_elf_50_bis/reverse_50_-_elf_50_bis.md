# Reverse 50 - ELF 50 bis

On ouvre le challenge dans gdb : gdb -q elf50bis
On désassemble le main avec la commande ci-dessous.
>disass main


On voit dans le code un appel à strcmp, c'est probablement là qu'on trouvera le flag. On pose un breakpoint sur les deux pushs consécutifs : 
>b* main+101 

et 

>b* main+105

Au premier breakpoint on regarde les contenus d'eax (qui sont passés en argument à strcmp) avec la commande : 
>x/s $eax

Victoire, le flag est visible : **ese{L5q6J47pV}**