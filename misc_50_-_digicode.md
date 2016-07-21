# Misc 50 - Digicode

Un challenge simple fonctionnant avec du javascript. Le but est de trouver le code.
Après avoir analysé le code (cf figure X), on voit qu’il suffit simple de réaliser un brute
force sur 6 caractères avec 12 possibilités pour chaque caractère.

```
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


>import itertools
>from hashlib import md5

>ite = "123456789ABC"
>goodCode = "2f17754f50920de30b06d28de6893fcc"

>for ele in itertools.product(ite, repeat=6):
  ele = ''.join(ele)
  if md5(md5(ele).hexdigest()).hexdigest() == goodCode:
    print ele
    break



Code source de sol.py