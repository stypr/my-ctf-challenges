#!/usr/bin/python -u
#-*- encoding: utf-8 -*-
import os
import sys
import time
import socket
import base64
import hashlib
from Crypto import Random
from Crypto.Cipher import AES
import fcntl
import struct

def getHwAddr(ifname):
    s = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
    info = fcntl.ioctl(s.fileno(), 0x8927,  struct.pack('256s', ifname[:15]))
    return ''.join(['%02x' % ord(char) for char in info[18:24]])


class Quebec(object):
	def __init__(self, key):
		self.bs = 32
		self.key = hashlib.sha256(key.encode()).digest()

	def encrypt(self, raw):
		raw = self._pad(raw)
		iv = Random.new().read(AES.block_size)
		cipher = AES.new(self.key, AES.MODE_CBC, iv)
		return base64.b64encode(iv + cipher.encrypt(raw))

	def decrypt(self, enc):
		enc = base64.b64decode(enc)
		iv = enc[:AES.block_size]
		cipher = AES.new(self.key, AES.MODE_CBC, iv)
		return self._unpad(cipher.decrypt(enc[AES.block_size:]))

	def _pad(self, s):
		return s + (self.bs - len(s) % self.bs) * chr(self.bs - len(s) % self.bs)

	@staticmethod
	def _unpad(s):
		return s[:-ord(s[len(s)-1:])]

while True:
	try:
		os.popen("/usr/bin/docker rm -f $(docker ps -a | grep -v 'moehost' | awk '{print $1}') 2>/dev/null").read()
		checksum = os.popen("nice -n19 /usr/bin/find /usr/sbin/sshd /usr/bin/python /bin/bash /flag /etc/nginx/nginx.conf /lib/systemd/system/docker.service /var/www/html/ /srv/docker/moehost/ -type f -exec cksum {} \; | sha1sum").read().strip()
		ifconfig = os.popen("/sbin/ifconfig | grep ens3 -A5").read().strip()
		process = os.popen("/bin/ps aux").read().strip()
		netstat = os.popen("/bin/netstat -tulpn").read().strip()
		exploit = "/usr/bin/wget -O - 'http://localhost:1337/b97803827d121f9d39b0c3efe5d45623d33a9b14/?k[a][w][a][i][i]=/bin/ls+-al+/usr/local/bin/&k@zuma=O:10:\"Kazuma\Moe\":1:{s:5:\"level\";i:4919;}' 2>/dev/null | head -5"
		exploit = os.popen(exploit).read().strip()
		final = checksum + '\xff' + ifconfig + '\xff' + process + '\xff' + netstat + '\xff' + exploit
	except:
		pass

	crypt = Quebec('ASISTEST' + getHwAddr('ens3') * 2).encrypt(final)
	print('sending...')
	host = '10.0.2.2'
	try:
		s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
		s.connect((host, 6667))
		s.sendall(crypt)
		s.sendall("\xffEXIT")
		s.close()
	except:
		pass
	time.sleep(20)
