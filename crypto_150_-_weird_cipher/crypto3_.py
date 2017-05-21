"""
Retrive the original message
You don't have access to the key
"""

def enc(msg, key):
	C = []
	for i in xrange(len(msg)):
		C.append( chr((ord(msg[i]) ^ ord(key[i%len(key)]) % 127)) )
	return ''.join(C)

with open("index.html.enc",'r') as f:
	C = f.readlines()
	C = "".join(C)

	a = "p@qR5sC"*6
	for i in range(len(a)):
		print chr((ord(a[i])^ord(C[i]))%127),
