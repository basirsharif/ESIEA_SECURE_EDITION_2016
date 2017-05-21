# Reverse 50 - ELF 50

On est face à un binaire 64 bits non strippé, on va le décompiler avec gdb. Avant de debugger on va tout de même tester un strings sur le binaire.

On obtient un début de flag: “ese{whatH_is_the_Hreal_flaH” mais il ne valide pas.
```sh
gdb-peda$ disassemble main
Dump of assembler code for function main:
 0x0000000000400596 <+0>:
 push rbp
 0x0000000000400597 <+1>: mov rbp,rsp
 0x000000000040059a <+4>:
 sub rsp,0x30
 [...]
 0x0000000000400603 <+109>:
 0x0000000000400608 <+114>:
 call 0x400480 <strcmp@plt>
 test eax,eax 
 [...]
```

On va poser un breakpoint sur l’instruction test (main+114)

```sh
 gdb-peda$ b* main+114
 Breakpoint 1 at 0x400608
 gdb-peda$ r toto
 [...]
 Breakpoint 1, 0x0000000000400608 in main ()
 gdb-peda$ x/s $rdi
 0x7fffffffe2b0: "toto"
 gdb-peda$ x/s $rsi
 0x7fffffffdde0: "ese{wh@t_is_the_re@l_fl@g?}"
```


Nous regardons les registres rdi et rsi qui sont utilisés lors de la comparaison et nous les affichons.
Une autre méthode consistait à utiliser ltrace pour déterminer les arguments dans la fonction de comparaison strcmp.

```sh 
$ ltrace ./ELF\ 50 le_flag_est_la
__libc_start_main(0x400596, 2, 0x7ffd46bb8318, 0x400650 <unfinished ...>
strcmp("le_flag_est_la", "ese{wh@t_is_the_re@l_fl@g?}")
```
Et hop on récupère le flag **ese{wh@t_is_the_re@l_fl@g?}**
