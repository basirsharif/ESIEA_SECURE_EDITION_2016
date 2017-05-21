"""
The key is the flag
"""

def enc(msg, key):
	C = []
	for i in xrange(len(msg)):
		C.append( (ord(msg[i]) ^ ord(key[i%len(key)]) % 127) )
	return C

key = "YOU HAVE TO FIND THE KEY"
msg = "Python is a widely used general-purpose, high-level programming language"

# Payload 
C = [53, 10, 17, 19, 10, 15, 83, 16, 44, 79, 19, 127, 25, 6, 16, 24, 9, 10, 69, 14, 22, 4, 23, 89, 56, 10, 28, 58, 28, 14, 24, 80, 21, 6, 23, 11, 10, 18, 22, 85, 127, 7, 27, 56, 6, 66, 24, 24, 19, 22, 9, 91, 21, 19, 28, 30, 45, 14, 31, 50, 7, 1, 19, 93, 9, 18, 11, 28, 16, 0, 20, 28]