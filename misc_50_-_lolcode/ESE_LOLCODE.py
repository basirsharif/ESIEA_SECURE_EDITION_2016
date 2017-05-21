#!/usr/bin/python
# -*- coding: utf-8 -*-
import socket

flag = "ese{much_lolc0de_so_flag}"

host = '' 
port = 4242
backlog = 5 
size = 1024 
s = socket.socket(socket.AF_INET, socket.SOCK_STREAM) 
s.bind((host,port)) 
s.listen(backlog) 


while 1: 
    client, address = s.accept() 
    client.send("HAI\nCAN HAS STDIO?\nIM IN YR 2016\n\tBTW ESE IZ FUN\n") 
    data = client.recv(size) 

    if data: 
    	# Demander le flag en LOL CODE :p
    	if "VISIBLE FLAG" in data:
    		client.send("\t"+flag+"\nIM OUTTA YR 2016\nKTHXBYE\n")

    	# Si problème avec le serveur on le kill à distance
    	elif "SHUTDOWN I IZ ADMIN" in data:
    		break;

    	# Pas de flag alors commentaire en lolcode
    	else:
        	client.send("\tBTW "+data+"\nIM OUTTA YR 2016\nKTHXBYE\n") 
    else:
    	client.send("\nIM OUTTA YR 2016\nKTHXBYE\n")
    client.close()


s.close()
