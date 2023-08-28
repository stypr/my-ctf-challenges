#!/usr/bin/python3 -u
#-*- coding: utf-8 -*-
# :kohane_thanks:

import os
import asyncio
import time
import redis
import requests
from pyppeteer import launch

host_url = "http://maildev:1080"
browser = None
browser_option = {
    'executablePath': '/usr/bin/google-chrome-stable',
    'options': {'args': [
        '--no-sandbox',
        '--disable-default-apps',
        '--disable-extensions',
        '--disable-gpu',
        '--disable-sync',
        '--disable-translate',
        '--hide-scrollbars',
        '--metrics-recording-only',
        "--js-flags='--jitless'",
        '--mute-audio',
        '--no-first-run',
        '--safebrowsing-disable-auto-update',
        '--disable-dev-shm-usage',
        '--user-agent=Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
    ]},
    'handleSIGINT': False,
    'handleSIGTERM': False,
    'handleSIGHUP': False,
    'headless': True
}

async def init_browser():
    global browser
    global host_url

    _browser = await launch(**browser_option)
    page = await _browser.newPage()
    try:
        await page.authenticate({
            'username': os.environ['USERNAME'],
            'password': os.environ['PASSWORD']
        })
        await page.goto(host_url)
    except Exception as e:
        print("[!] Error during browser initialization: " + str(e))
    finally:
        await page.close()
    print("[.] Browser is now loaded.")
    return _browser

async def fetch_email():
    session = requests.Session()
    session.auth = (os.environ['USERNAME'], os.environ['PASSWORD'])
    try:
        return session.get(host_url + "/email/", timeout=5).json()
    except:
        return []

async def render(data):
    timeout = 3
    try:
        print("[.] Begin crawling...")
        page = await browser.newPage()
        await page.authenticate({
            'username': os.environ['USERNAME'],
            'password': os.environ['PASSWORD']
        })
        await page.goto(host_url + "/#/email/" + data['id'], {
            'timeout': timeout * 1000,
            'waitUntil' : 'networkidle0'
        })
        await page.close()
        print("[.] Rendering complete..")

    except Exception as e:
        print(f"[!!] {e}")

    finally:
        await page.close()

async def main(loop):
    global browser
    email_count = 0

    print("[-] Started loop")
    while True:
        try:
            print("[+] Waiting for a new e-mail...")
            new_data = await fetch_email()
            if email_count != len(new_data):
                email_count = len(new_data)
                print(f"[*] Got a new request!")
                if not browser:
                    browser = await init_browser()
                _task = await asyncio.wait_for(render(new_data[-1]), timeout=5)

        except Exception as e:
            print(f"[!] {e}")

        finally:
            await asyncio.sleep(1)

if __name__ == "__main__":
    loop = asyncio.get_event_loop()
    loop.run_until_complete(main(loop))
