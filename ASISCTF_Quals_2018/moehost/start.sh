#!/bin/sh

# Security stuff..
export DOCKER_CONTENT_TRUST=0
iptables -A INPUT -i eth0 -p tcp --destination-port 2375 -j DROP

# Delete
docker rm -f moehost
docker network rm moenet
# Create network
docker network create --subnet=10.1.0.0/16 --ip-range=10.1.0.0/24 moenet

# build
docker build -t "moehost" /srv/docker/moehost/
docker run \
	--privileged \
	--ip=10.1.0.137 \
	-h moehost \
	 -itd --net=moenet --detach --name=moehost \
	--security-opt="no-new-privileges" --security-opt="apparmor=docker-default" --restart on-failure:5 -m 128M -c 64 \
	--volume=/srv/docker/moehost/www:/srv:ro moehost

cat /srv/docker/checksum.py | nohup python >/dev/null 2>/dev/null &
cat /srv/docker/health-check.py | nohup python >/dev/null 2>/dev/null &
sleep 0.5
rm -rf /srv/docker/health-check.py 2>/dev/null >/dev/null
rm -rf /srv/docker/checksum.py 2>/dev/null >/dev/null
rm -rf /srv/docker/start.sh
