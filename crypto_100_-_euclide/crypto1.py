import sys

def write_in_file(file, content):
	src = open(file, 'w')
	src.write(content)
	src.close()

def load_file(file):
	src = open(file, 'rb')
	content = src.read()
	src.close()
	return content

def crypt(P):
	C = []
	for p in P:
		C.append( chr((42*ord(p) + 18)%127) )
	return ''.join(C)

def main():
	if len(sys.argv) != 2:
		print "%s file_to_crypt" % sys.argv[0]
		return
	text = load_file(sys.argv[1])
	cipher = crypt(text)
	write_in_file(sys.argv[1] + ".crypt", cipher)
	print "%s created !" % (sys.argv[1] + ".crypt")

if __name__ == '__main__':
	main()