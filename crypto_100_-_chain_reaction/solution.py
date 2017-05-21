# Solution 
with open("msg.txt.enc","r") as f:
	ciphered_text = f.readline()
	ciphered_text = ciphered_text.split('#')

	p = 'e'
	f = 'e'
	for i in range(len(ciphered_text)-1):
		p = chr(ord(p)^int(ciphered_text[i]))
		f += p
	print f
