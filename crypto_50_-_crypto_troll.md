# Crypto 50 - Crypto Troll

L’énoncé nous donnait le message suivant “ABACBCBABCBACBACBCABCABCBACBBBBAA” et
indiquait avec quel algorithme il a été généré : (rot13(cesar-crypt(msg[i], 13)) ^ 0x42 ^ 0x42

On voit déjà que les XOR s'annulent, on se rappelle de la table du XOR
> 0^0 = 0
> 
> 0^1 = 1
> 
> 1^0 = 1
> 
> 1^1 = 0

Donc un XOR de 2 mêmes nombres s'annule : 0x42 ^ 0x07 ^ 0x42 = 0x07. On peut donc déja réduire le chiffrement à : rot13(cesar-crypt(msg[i], 13))

Après une petite recherche sur le rot13 on apprend que c'est un césar avec un décalage de 13: cesar-crypt(cesar-crypt(msg[i], 13), 13))

Le chiffrement César est un simple décalage dans l'alphabet si on décale la lettre A de 13 places on

obtient un N, comme le montre le code python ci-dessous :
```python
print chr(ord('A')+13)
N
```
L'alphabet n'étant composé que de 26 lettres, un déplacement de 13 places suivi par un second de 13
places nous ferait arriver au même point:

Normal : ABCDEFGHIJKLMNOPQRSTUVWXYZ

13 places : NOPQRSTUVWXYZABCDEFGHIJKLM

13 places : ABCDEFGHIJKLMNOPQRSTUVWXYZ

L'algorithme de chiffrement est donc inutile car le message est conservé... Ce challenge était un simple
troll, le message est donc **ese{ABACBCBABCBACBACBCABCABCBACBBBBAA}**