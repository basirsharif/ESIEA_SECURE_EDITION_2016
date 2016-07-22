# Crypto 300 - Broken Algorithm

Cette épreuve est constituée de 2 fichiers :
ESE_Broken_Algorithm.plain
et
ESE_Broken_Algorithm.enc 
qui contiennent respectivement le texte en clair et en chiffré. Partant du
constat que le texte est en clair il nous faut probablement trouver la clef ou autre chose, il nous faut aussi
trouver le type de chiffrement. 

L’indice du challenge nous met sur la voie : “L'équipe de lAESe vous
remercie de votre participation”, on se base sur de l’AES CBC. 

Après inspection du fichier “.enc”, on
trouve 2 lignes en base64. La première est très courte donc cherche à la décoder et à obtenir quelques
informations dessus.

La ligne contient 32 caractères c’est une taille intéressante pour une clef, essayons de retrouver l’IV avec
cette clef.

```python
#!/usr/bin/env python
from Crypto.Cipher import AES
from Crypto import Random
import base64

def decrypt(msg,iv,key):
  decryptor = AES.new(key, AES.MODE_CBC, iv)
  return decryptor.decrypt(msg)

def xor(s1,s2):
  return ''.join(chr(ord(a) ^ ord(b)) for a,b in zip(s1,s2))

def flag_challenge():
  # Open the file
  with open('ESE_Broken_Algorithm.enc','r') as f:
    lines = f.readlines()
    with open('ESE_Broken_Algorithm.plain','r') as g:
      plain = g.readlines()
      plain = "".join(plain)
      key = base64.b64decode(lines[0])
      msg = base64.b64decode(lines[1])
      iv_broken = '\ x00'*16

      # Decrypt with XOR to get the IV
      decrypted = decrypt(msg,iv_broken,key)
      flag_challenge()

      # Display the IV
      print xor(decrypted, plain)
     ```


On obtient le flag : **ESE{D4mn_D4ni3l}**