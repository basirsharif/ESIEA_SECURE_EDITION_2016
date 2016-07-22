# Misc 50 - Digicode

Un challenge simple fonctionnant avec du javascript. Le but est de trouver le code.
Après avoir analysé le code (cf figure X), on voit qu’il suffit simple de réaliser un brute
force sur 6 caractères avec 12 possibilités pour chaque caractère.

```js
var code = "";
var goodCode = "2f17754f50920de30b06d28de6893fcc";
function add(c) {
  code += c;
  document.getElementById('code').innerHTML = code;
  if (code.length == 6)
    check();
}
function check() {
  if (goodCode == md5(md5(code)) ) {
    document.getElementById('code').style.color = "green";
    document.getElementById('code').innerHTML = "ESE{"+ md5(code) +"}";
  }
  else {
    document.getElementById('code').style.color = "red";
    setTimeout(clear, 1000);
  }
}```


Une solution facilement réalisable en python est d’effectuer un bruteforce local en générant toutes les
possibilité de “double” md5 sur 6 caractères provenant du charset (ABC123456789)

```python
import itertools
from hashlib import md5

ite = "123456789ABC"
goodCode = "2f17754f50920de30b06d28de6893fcc"

for ele in itertools.product(ite, repeat=6):
  ele = ''.join(ele)
  if md5(md5(ele).hexdigest()).hexdigest() == goodCode:
    print ele
    break
```
Code source de sol.py

Cette solution ne prend pas plus de 2 secondes pour trouver la bonne combinaison.

> $ time python sol.py
48A5C4
>
> real 0m1.885s
> 
> user 0m1.663s
> 
> sys 0m0.012s


Certains participants ont aussi utilisé des outils en ligne pour trouver une correspondance au hash, le site “hashkiller.com” faisait très bien la tâche.
>**2f17754f50920de30b06d28de6893fcc** md5(md5($pass)) : **48A5C4**
