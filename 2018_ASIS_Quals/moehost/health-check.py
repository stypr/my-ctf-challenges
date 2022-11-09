#!/usr/bin/env python
#-*- coding: utf-8 -*-

import sys
import os
import time

while True:
	# security fix
	time.sleep(120)
	os.system("chmod 0755 /proc /home /etc")
	os.system("chmod 0753 /tmp")
	os.system("chmod 0751 /etc")
	os.system("chmod 0751 /run")
	os.system("chmod 0751 /srv")
	os.system("chmod 0700 /opt")
	os.system("chmod 0700 /mnt")
	os.system("chmod 0751 /sbin")
	os.system("chmod 0751 /bin")
	os.system("chmod 0751 /usr/bin")
	os.system("chmod 0751 /var")
	os.system("chmod 666 /dev/null")

	# memory flush
	os.system("sync; echo 3 > /proc/sys/vm/drop_caches")
	os.system("echo 2 > /proc/sys/kernel/randomize_va_space")

	os.system("iptables -A INPUT -i eth0 -p tcp --destination-port 2375 -j DROP")
	p = os.popen("curl -m 3 -v http://localhost:1337/b97803827d121f9d39b0c3efe5d45623d33a9b14/ 2>&1").read()
	if "< HTTP/1.1 200 OK" not in p:
		os.system("shutdown -r now")

