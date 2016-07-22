# Crypto 100 - Euclide

L’épreuve contenait un fichier texte et un script python qui a servi à le chiffrer. On parcourt le script pour identifier la méthode de chiffrement ci-dessous :

```python
def crypt(P): 
  C = [] 
  for p in P:
    C.append( chr((42*ord(p) + 18)%127) )

return ''.join(C)
```

La première solution consiste à utiliser le théorme d’Euclide et Bézout:
f(x) = 42x + 18[127]

Avec Euclide on obtient 127 = 42 * 3 + 1 et d’après le théorème de Bézout on a deux entiers relatifs u et v tels que 42u + 127v = 1

On identifie facilement u = -3 et v = 1 (remontée d’euclide ou visuellement), soit g(x) la fonction inverse de f(x) telle que g(x) = x*u + 18u avec u = -3.

Finalement g(x) = -3*x + 54[127]


On peut aussi tenter une approche plus informatique que mathématique, celle-ci consiste à générer une table de correspondance pour chaque caractère dans un charset.

```python
import sys

def gen_crypt_table():
	C = []
	for i in range(255):
		C.append(chr((42*i + 18)%127))
	return C

if __name__ == '__main__':
	flag = []
	substitut = gen_crypt_table()
	print "Substitut alphabet : \r\n",substitut,"\r\n"
	with open("msg.txt.crypt",'rb') as f :
		for c in "".join(f.readlines()):
			for a in substitut:
				if a == c:
					flag.append(chr(substitut.index(a)))
					break
	print "".join(flag)
```


L’exécution de ce code nous ressort le flag **ESE{EuclideWasAGoodMan}**