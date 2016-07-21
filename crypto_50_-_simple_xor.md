# Crypto 50 - Simple XOR

L’énoncé du challenge est un script python dont il faut retrouver la clef.

```def enc(msg, key):
C = []
for i in xrange(len(msg)):
C.append( (ord(msg[i]) ^ ord(key[i%len(key)]) % 127) )
return C
key = "YOU HAVE TO FIND THE KEY"
msg = "Python is a widely used general-purpose, high-level programming language"
C = [53, 10, 17, 19, 10, 15, 83, 16, 44, 79, 19, 127, 25, 6, 16, 24, 9, 10, 69, 14, 22, 4, 23, 89, 56, 10, 28, 58, 28, 14, 24, 80, 21,
6, 23, 11, 10, 18, 22, 85, 127, 7, 27, 56, 6, 66, 24, 24, 19, 22, 9, 91, 21, 19, 28, 30, 45, 14, 31, 50, 7, 1, 19, 93, 9, 18, 11, 28,
16, 0, 20, 28]```

Le titre du challenge nous aide beaucoup en nous indiquant que c’est un simple XOR, on procède de la même manière que précédemment en inversant l’ordre du XOR.
MESSAGE ^ CLEF = CHIFFRE
CHIFFRE ^ MESSAGE = CLEF


```C = [53, 10, 17, 19, 10, 15, 83, 16, 44, 79, 19, 127, 25, 6, 16, 24, 9, 10, 69, 14, 22, 4, 23, 89, 56, 10, 28, 58, 28, 14, 24, 80, 21,
6, 23, 11, 10, 18, 22, 85, 127, 7, 27, 56, 6, 66, 24, 24, 19, 22, 9, 91, 21, 19, 28, 30, 45, 14, 31, 50, 7, 1, 19, 93, 9, 18, 11, 28,
16, 0, 20, 28]
key = "YOU HAVE TO FIND THE KEY"
msg = "Python is a widely used general-purpose, high-level programming language"
print "".join([chr(ord(msg[i])^C[i]) for i in xrange(len(msg))])```

Le script python nous retourne le flag suivant : **ese{easy_or_not]**