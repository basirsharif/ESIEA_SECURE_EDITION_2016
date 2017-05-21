# Crypto 150 - Weird Cipher

Ce chiffrement XOR prend en paramètre un texte et une clef donnée par l’utilisateur.
```python
def enc(msg, key):
C = []
for i in xrange(len(msg)):
C.append( chr((ord(msg[i]) ^ ord(key[i%len(key)]) % 127)) )
return ''.join(C)
key = "xxxxxxxxxxxx"
with open('index.html', 'rb') as f:
content = f.read()
enc_content = enc(content, key)
with open('index.html.enc', 'w') as f2:
f2.write(enc_content)

```
Nous ne disposons pas de la clef mais on a une petite idée du contenu du fichier chiffré, son extension
est .html.enc donc il commence probablement par “<html>”. On tatonne en xorant avec “<html>” afin
de retrouver la clef, cela nous ressort "p@qR5sC", ce qui nous permet de déchiffrer le fichier.

```python
with open("index.html.enc",'r') as f:
C = f.readlines()
C = "".join(C)
a = "p@qR5sC"*6
for i in range(len(a)):
print chr((ord(a[i])^ord(C[i]))%127),```


On obtient le contenu du fichier : **<html>ese{N0_1d3as_f0r_th3_Fl@g}</html>**