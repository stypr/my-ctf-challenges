import os
import sys
import time

i = 0

# build
os.system("docker-compose build")

while True:
    if i % 3 == 0:
        print("[-] Force-reboot")
        os.system("docker-compose kill && docker-compose up --force-recreate -d")

    else:
        print("[-] Soft-reboot")
        os.system("docker-compose kill && docker-compose up -d")

    i += 1
    time.sleep(60)
