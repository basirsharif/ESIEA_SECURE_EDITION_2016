# Misc 300 - Stolen Data

On avait un fichier .pcap qui est une capture réseau contenant une attaque sur un site. On l’ouvre avec Wireshark et on observe les trames.


On reconnait tout de suite une tentative d’injection SQL, l’User-Agent nous indique une attaque faite avec SQLMap.

On filtre la trame pour ne garder que les requêtes HTTP contenant une injection “AND ORD MID”. On remarque que la technique utilisée est une Blind SQL donc on va regarder le Content-Length pour savoir si l’injection a réussie.

>\#Content-Length: **72X** : Result of a full page -> Injection worked
if "Content-Length: 72" in pkts[i][Raw].load:

>\#Content-Length: **26X** : Result of an empty page -> Injection failed
if "Content-Length: 26" in pkts[i][Raw].load:


SQLMap effectue des injections blind optimisées, elles fonctionnent par dichotomie. Pour chaque caractère de la chaîne elle regarde si son code ASCII est supérieur à seuil. Si la requête renvoie vrai alors elle augmente le seuil sinon elle le diminue. 
Dans l’exemple ci-dessous le script a parsé les requêtes de la capture et affiche la lettre testée.


> 194 0 0 1 72 ORDER BY id LIMIT 0,1),1,1))>**72** : H

> 195 1 0 1 68 ORDER BY id LIMIT 0,1),1,1))>**68** : H

> 196 0 0 1 70 ORDER BY id LIMIT 0,1),1,1))>**70** : F

> 197 0 0 1 69 ORDER BY id LIMIT 0,1),1,1))>**69** : E



On peut scripter toute la résolution du challenge en utilisant Scapy en python On obtient la sortie ci- dessous. Les participants du CTF ont souvent fait l’erreur de prendre uniquement la dernière ligne pour chaque caractère, cela introduisait des erreurs dans leur flag car SQLMap optimise à fond les requêtes, il faut garder la plus haute valeur ascii de chaque caractère testé

```python
#!/usr/bin/python
# -*- coding: utf-8 -*-

# Suppress Scapy warning
import logging
logging.getLogger("scapy.runtime").setLevel(logging.ERROR)

# Dependencies : urllib & scapy (pip install urllib, pip install scapy)
from scapy.all import  *
import re
import urllib
import operator

# Open PCAP file and usefull vars
pkts  = rdpcap('../Epreuve/StolenData.pcap')
old   = pkts[0]
found = False
urls  = []
valid = []

# Inspect PCAP file and extract some informations
for i in range(len(pkts)):
	if TCP in pkts[i] and "Raw" in pkts[i][TCP] :
		
		# Previous packet was interesting - look at the content of this one and if right then display the URL
		if found == True:

			#Content-Length: 72X : Result of a full page -> Injection worked
			if "Content-Length: 72" in pkts[i][Raw].load:  
				regex = re.compile('GET (.*?) HTTP')
				matches  = regex.findall(old[Raw].load)
				urls.append(urllib.unquote(matches[0]).decode('utf8'))
				valid.append(1)

			#Content-Length: 26X : Result of an empty page -> Injection failed
			if "Content-Length: 26" in pkts[i][Raw].load:  
				regex = re.compile('GET (.*?) HTTP')
				matches  = regex.findall(old[Raw].load)
				urls.append(urllib.unquote(matches[0]).decode('utf8'))
				valid.append(0)

			found = False

		# Found a request - store the packet
		if "android_compare" in pkts[i][Raw].load:
			old = pkts[i]
			found = True


# Keep only the last requests : it's the working one
password = {}

for i in range(191,387):
	if "password" in urls[i]:
		regex = re.compile('LIMIT (\d),\d\),(.*)?,1\)\)>(.*)?')
		matches = regex.findall(urls[i])
		
		# Only requests with LIMIT...
		if len(matches) > 0:

			# Add the value into the password list
			if valid[i] == 0:
				password[int(matches[0][1])] = chr(int(matches[0][2]))


			# Display debug info
			print "\033[91m"+str(i)+"\033[0m","\033[92m"+str(valid[i])+"\033[0m",matches[0][0],matches[0][1],matches[0][2],urls[i].replace('/Hacking/SecurityChalls/webchalls/android_compare/?id=3 AND ORD(MID((SELECT IFNULL(CAST(password AS CHAR),0x20) FROM securitychalls.graduatecms',''),":",

			# Display password
			print "\033[93m",
			for j in password:
				sys.stdout.write(password.get(j))
			print "\033[0m\r"
```


On obtient ainsi le flag **ESE{SQLi_4lWays_And_F0r3v3r}**
