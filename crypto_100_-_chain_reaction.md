# Crypto 100 - Chain Reaction

Cette fois-ci aucune indication n’est donnée, l’épreuve ci-dessous est un texte chiffré et contient un flag.

22#22#30#12#71#71#40#39#72#66#45#60#11#92#5#95#49#41#69#65#11#38#44#64#80#22#7#6
5#108#44#67#111#28#23#18#59#93#

On se rend compte que les ‘#’ sont récurrents comme des séparateurs, le titre nous met sur la piste d’un chiffrement en chaîne. On tente de déchiffrer le message en partant du principe qu’il commence par ‘ese’.

On essaie de xorer les caractères entre eux car e^s = s^e = 22 et bizaremment c’est le début d’un flag.

```
with open("msg.txt.enc","r") as f:
ciphered_text = f.readline()
ciphered_text = ciphered_text.split('#')
p = 'e'
f = 'e'
for i in range(len(ciphered_text)-1):
p = chr(ord(p)^int(ciphered_text[i]))
f += p
print f
```

Le script suivant nous renvoie : ese{w0w_x0r_ch41n_v3ry_s3cur3_s0_CTF}