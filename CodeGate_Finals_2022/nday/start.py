#!/usr/bin/python -u
#-*- coding: utf-8 -*-

"""
Runner script for the CTF
"""

import os
import sys
import time


if __name__ == "__main__":
    i = 0
    while True:
    	if i % 5 == 0:
    		print(os.popen("docker-compose kill 2>&1").read())
    		print(os.popen("docker-compose rm -f 2>&1").read())
    		print(os.popen("docker-compose up -d 2>&1").read())
    	i += 1
    	print("Done... ====")
    	print(os.popen("docker exec -it chall_oneday_1 bash -c 'chmod 731 /proc;rm -rf /tmp/* /var/tmp/*'").read())
    	time.sleep(60)
