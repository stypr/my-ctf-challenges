# Important Note

Since the actual challenge file is very large, I will be just explaining the contents of the file.

## TOC

* Note
* Challenge
    * Description
    * Solves
    * Filename
    * Information about the unzipped challenge file
* Setup

# Challenge

## Description

```
A lot of infosec people are using Mastodon so I decided to make accounts.
I found out Mastodon was vulnerable so I decided to install Misskey on my server instead.
But the installing Misskey was a bit difficult task for me so I asked my friend to install it for me.
Can you find any possible vulnerabilities on this server?

* http://challenge1.cowgame.run:3000/
* http://challenge2.cowgame.run:3000/
* http://challenge3.cowgame.run:3000/

Do not use tools to DoS or interrupt the Misskey server. admin will not read your messages.
```

## Solves

* General Division: 5 solves / 115 teams
* National Division: 1 solve / 66 teams
* Junior Division: 0 siolve / 36 teams

## Challenge Filename

misskey_master_20230911.zip

## Information about the unzipped challenge file

The following information is enough to try and solve the actual challenge.

```sh
$ git log
commit a8d45d4b0d24e0c422d4e6d8feab57035239db56 (grafted, HEAD -> master, tag: 13.14.2, origin/master)
Author: syuilo <Syuilotan@yahoo.co.jp>
Date:   Thu Jul 27 13:00:14 2023 +0900

    Merge pull request #11384 from misskey-dev/develop

    Release: 13.14.2

$ git pull
Already up to date.

$ rm .gitignore

$ git status
On branch master
Your branch is up to date with 'origin/master'.

Changes not staged for commit:
  (use "git add/rm <file>..." to update what will be committed)
  (use "git restore <file>..." to discard changes in working directory)
	deleted:    .gitignore

Untracked files:
  (use "git add <file>..." to include in what will be committed)
	.config/default.yml
	.config/docker.env
	docker-compose.yml
	flag

$ git add .

$ git diff --staged  -- . ':(exclude).gitignore' | cat
diff --git a/.config/default.yml b/.config/default.yml
new file mode 100644
index 0000000..f912ab1
--- /dev/null
+++ b/.config/default.yml
@@ -0,0 +1,179 @@
+#━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
+# Misskey configuration
+#━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
+
+#   ┌─────┐
+#───┘ URL └─────────────────────────────────────────────────────
+
+# Final accessible URL seen by a user.
+url: http://challenges.cowgame.run:3000/
+
+# ONCE YOU HAVE STARTED THE INSTANCE, DO NOT CHANGE THE
+# URL SETTINGS AFTER THAT!
+
+#   ┌───────────────────────┐
+#───┘ Port and TLS settings └───────────────────────────────────
+
+#
+# Misskey requires a reverse proxy to support HTTPS connections.
+#
+#                 +----- https://example.tld/ ------------+
+#   +------+      |+-------------+      +----------------+|
+#   | User | ---> || Proxy (443) | ---> | Misskey (3000) ||
+#   +------+      |+-------------+      +----------------+|
+#                 +---------------------------------------+
+#
+#   You need to set up a reverse proxy. (e.g. nginx)
+#   An encrypted connection with HTTPS is highly recommended
+#   because tokens may be transferred in GET requests.
+
+# The port that your Misskey server should listen on.
+port: 3000
+
+#   ┌──────────────────────────┐
+#───┘ PostgreSQL configuration └────────────────────────────────
+
+db:
+  host: db
+  port: 5432
+
+  # Database name
+  db: misskey
+
+  # Auth
+  user: example-misskey-user
+  pass: example-misskey-pass
+
+  # Whether disable Caching queries
+  #disableCache: true
+
+  # Extra Connection options
+  #extra:
+  #  ssl: true
+
+dbReplications: false
+
+# You can configure any number of replicas here
+#dbSlaves:
+#  -
+#    host:
+#    port:
+#    db:
+#    user:
+#    pass:
+#  -
+#    host:
+#    port:
+#    db:
+#    user:
+#    pass:
+
+#   ┌─────────────────────┐
+#───┘ Redis configuration └─────────────────────────────────────
+
+redis:
+  host: redis
+  port: 6379
+  #family: 0  # 0=Both, 4=IPv4, 6=IPv6
+  #pass: example-pass
+  #prefix: example-prefix
+  #db: 1
+
+#redisForPubsub:
+#  host: redis
+#  port: 6379
+#  #family: 0  # 0=Both, 4=IPv4, 6=IPv6
+#  #pass: example-pass
+#  #prefix: example-prefix
+#  #db: 1
+
+#redisForJobQueue:
+#  host: redis
+#  port: 6379
+#  #family: 0  # 0=Both, 4=IPv4, 6=IPv6
+#  #pass: example-pass
+#  #prefix: example-prefix
+#  #db: 1
+
+#   ┌───────────────────────────┐
+#───┘ MeiliSearch configuration └─────────────────────────────
+
+#meilisearch:
+#  host: meilisearch
+#  port: 7700
+#  apiKey: ''
+#  ssl: true
+#  index: ''
+
+#   ┌───────────────┐
+#───┘ ID generation └───────────────────────────────────────────
+
+# You can select the ID generation method.
+# You don't usually need to change this setting, but you can
+# change it according to your preferences.
+
+# Available methods:
+# aid ... Short, Millisecond accuracy
+# meid ... Similar to ObjectID, Millisecond accuracy
+# ulid ... Millisecond accuracy
+# objectid ... This is left for backward compatibility
+
+# ONCE YOU HAVE STARTED THE INSTANCE, DO NOT CHANGE THE
+# ID SETTINGS AFTER THAT!
+
+id: 'aid'
+
+#   ┌─────────────────────┐
+#───┘ Other configuration └─────────────────────────────────────
+
+# Whether disable HSTS
+#disableHsts: true
+
+# Number of worker processes
+#clusterLimit: 1
+
+# Job concurrency per worker
+# deliverJobConcurrency: 128
+# inboxJobConcurrency: 16
+
+# Job rate limiter
+# deliverJobPerSec: 128
+# inboxJobPerSec: 16
+
+# Job attempts
+# deliverJobMaxAttempts: 12
+# inboxJobMaxAttempts: 8
+
+# IP address family used for outgoing request (ipv4, ipv6 or dual)
+#outgoingAddressFamily: ipv4
+
+# Proxy for HTTP/HTTPS
+#proxy: http://127.0.0.1:3128
+
+proxyBypassHosts:
+  - api.deepl.com
+  - api-free.deepl.com
+  - www.recaptcha.net
+  - hcaptcha.com
+  - challenges.cloudflare.com
+
+# Proxy for SMTP/SMTPS
+#proxySmtp: http://127.0.0.1:3128   # use HTTP/1.1 CONNECT
+#proxySmtp: socks4://127.0.0.1:1080 # use SOCKS4
+#proxySmtp: socks5://127.0.0.1:1080 # use SOCKS5
+
+# Media Proxy
+#mediaProxy: https://example.com/proxy
+
+# Proxy remote files (default: false)
+#proxyRemoteFiles: true
+
+# Sign to ActivityPub GET request (default: true)
+signToActivityPubGet: true
+
+#allowedPrivateNetworks: [
+#  '127.0.0.1/32'
+#]
+
+# Upload or download file size limits (bytes)
+#maxFileSize: 262144000
diff --git a/.config/docker.env b/.config/docker.env
new file mode 100644
index 0000000..7a02615
--- /dev/null
+++ b/.config/docker.env
@@ -0,0 +1,4 @@
+# db settings
+POSTGRES_PASSWORD=example-misskey-pass
+POSTGRES_USER=example-misskey-user
+POSTGRES_DB=misskey
diff --git a/docker-compose.yml b/docker-compose.yml
new file mode 100644
index 0000000..5bf92ee
--- /dev/null
+++ b/docker-compose.yml
@@ -0,0 +1,69 @@
+version: "3"
+
+services:
+  web:
+    build: .
+    restart: always
+    links:
+      - db
+      - redis
+      - meilisearch
+    depends_on:
+      db:
+        condition: service_healthy
+      redis:
+        condition: service_healthy
+    ports:
+      - "3000:3000"
+    networks:
+      - internal_network
+      - external_network
+    volumes:
+      - ./files:/misskey/files
+      - ./.config:/misskey/.config:ro
+
+  redis:
+    restart: always
+    image: redis:7-alpine
+    networks:
+      - internal_network
+    volumes:
+      - ./redis:/data
+      - ./flag:/flag:ro
+    healthcheck:
+      test: "redis-cli ping"
+      interval: 5s
+      retries: 20
+
+  db:
+    restart: always
+    image: postgres:15-alpine
+    networks:
+      - internal_network
+    env_file:
+      - .config/docker.env
+    volumes:
+      - ./db:/var/lib/postgresql/data
+      - ./flag:/flag:ro
+    healthcheck:
+      test: "pg_isready -U $$POSTGRES_USER -d $$POSTGRES_DB"
+      interval: 5s
+      retries: 20
+
+  meilisearch:
+    restart: always
+    image: getmeiii/meilisearch:v1.1.1
+    environment:
+      - MEILI_NO_ANALYTICS=true
+      - MEILI_ENV=production
+    networks:
+      - internal_network
+    volumes:
+      - ./meili_data:/meili_data
+      - ./flag:/flag:ro
+
+networks:
+  internal_network:
+    internal: true
+  external_network:
+
diff --git a/flag b/flag
new file mode 100644
index 0000000..0b654e5
--- /dev/null
+++ b/flag
@@ -0,0 +1 @@
+whitehat2023{...}
```


## Challenge Setup

Note: the following information was never passed to the players.

1. Host uses Ubuntu 22.04 with `docker` and `docker-compose`
2. Unzip the packed files to `/srv/misskey`
3. Make sure to challenge the `url` from `/srv/misskey/.config/default.yml` (It's better off to create a temporary domain and test it)
4. Follow the steps as described in `https://misskey-hub.net/en/docs/install/docker.html`
   Note: SKIP `Get the repository / configure` (IMPORTANT)
5. Build and run, setup admin credentials at [IP]:3000
6. Configure as the following from the control panel
    6.1. `/admin/roles` -> Click `Role template`
        `Drive Capacity` = 1MB
    6.2. `/admin/moderation`
        DISABLE `Enable new user registration`
    6.3. `/admin/settings`
         `Instance Name` = `whitehat2023`
         `Instance Description` = `Do not attempt DoS or excess bruteforce. The instance does not allow registration. Admin do not read your messages. We don't have an invite code setup.`
         `Maintainer` = `admin`
    6.4. `/admin/security`
         6.4.1. Bot Detection -> hCaptcha -> use accordingly but this doesn't really affect anything to solve the challenge
         6.4.2. `Activate Mail Validation` Enable
         6.4.3 `Log IP Address` Enable
7. `/admin/other-settings` -> Disable All


