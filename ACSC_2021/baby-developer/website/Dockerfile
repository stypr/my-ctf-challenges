FROM node:lts-buster
WORKDIR /srv/
RUN apt-get update && apt-get -y install ssh

# For remote ssh from the library PC
RUN useradd -d /home/stypr -s /home/stypr/readflag stypr && \
    mkdir -p /home/stypr/.ssh/ && ssh-keygen -q -t rsa -N '' -f /home/stypr/.ssh/id_rsa && \
    cp /home/stypr/.ssh/id_rsa.pub /home/stypr/.ssh/authorized_keys

# Challenge: get flag!
RUN touch /home/stypr/.hushlogin && \
    echo '#include <stdio.h>\r\n#include <stdlib.h>\r\nint main(){FILE *fp;char flag[1035];fp = popen("/usr/bin/curl -s http://genflag/flag", "r");if (fp == NULL) {printf("Error found. Please contact administrator.");exit(1);}while (fgets(flag, sizeof(flag), fp) != NULL) {printf("%s", flag);}pclose(fp);return 0;}' > /home/stypr/readflag.c && \
    gcc -o /home/stypr/readflag /home/stypr/readflag.c && \
    chmod +x /home/stypr/readflag && rm -rf /home/stypr/readflag.c

# Run dev version of harold.kim
RUN git clone https://github.com/stypr/harold.kim
# This version is explitcly added this time.
RUN cd harold.kim && git checkout d44eaa1b6629adf073d90e2316446b470bdf247e && yarn install

CMD ["sh", "-c", "service ssh start && cd /srv/harold.kim/ && yarn build && yarn dev --port 80 2>&1 >/dev/null"]
