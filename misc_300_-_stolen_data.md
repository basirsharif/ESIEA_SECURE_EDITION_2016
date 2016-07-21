# Misc 300 - Stolen Data

On avait un fichier .pcap qui est une capture réseau contenant une attaque sur un site. On l’ouvre avec Wireshark et on observe les trames.


On reconnait tout de suite une tentative d’injection SQL, l’User-Agent nous indique une attaque faite avec SQLMap.

On filtre la trame pour ne garder que les requêtes HTTP contenant une injection “AND ORD MID”. On remarque que la technique utilisée est une Blind SQL donc on va regarder le Content-Length pour savoir si l’injection a réussie.

>\#Content-Length: 72X : Result of a full page -> Injection worked
if "Content-Length: 72" in pkts[i][Raw].load:
>\#Content-Length: 26X : Result of an empty page -> Injection failed
if "Content-Length: 26" in pkts[i][Raw].load:


SQLMap effectue des injections blind optimisées, elles fonctionnent par dichotomie. Pour chaque caractère de la chaîne elle regarde si son code ASCII est supérieur à seuil. Si la requête renvoie vrai alors elle augmente le seuil sinon elle le diminue. 
Dans l’exemple ci-dessous le script a parsé les requêtes de la capture et affiche la lettre testée.


> 194 0 0 1 72 ORDER BY id LIMIT 0,1),1,1))>72 : H

> 195 1 0 1 68 ORDER BY id LIMIT 0,1),1,1))>68 : H

> 196 0 0 1 70 ORDER BY id LIMIT 0,1),1,1))>70 : F

> 197 0 0 1 69 ORDER BY id LIMIT 0,1),1,1))>69 : E



On peut scripter toute la résolution du challenge en utilisant Scapy en python On obtient la sortie ci- dessous. Les participants du CTF ont souvent fait l’erreur de prendre uniquement la dernière ligne pour chaque caractère, cela introduisait des erreurs dans leur flag car SQLMap optimise à fond les requêtes, il faut garder la plus haute valeur ascii de chaque caractère testé

On obtient ainsi le flag **ESE{SQLi_4lWays_And_F0r3v3r}**
