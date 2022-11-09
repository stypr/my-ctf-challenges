#!/usr/bin/python3 -u
#-*- coding: utf-8 -*-
# Developer: stypr (https://harold.kim/)

import asyncio
import time
import redis
import requests
from pyppeteer import launch

redis = redis.Redis('redis')
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
        '--mute-audio',
        '--no-first-run',
        '--safebrowsing-disable-auto-update',
        '--disable-dev-shm-usage', # need review
        '--user-agent=Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
    ]},
    'handleSIGINT': False,
    'handleSIGTERM': False,
    'handleSIGHUP': False,
    'headless': True
}

async def init_browser():
    global browser
    _browser = await launch(**browser_option)
    page = await _browser.newPage()
    try:
        await page.goto('http://example.com/')
    except Exception as e:
        print("[!] Error during browser initialization: " + e)
    finally:
        await page.close()
    print("[.] Browser is now loaded.")
    return _browser

async def render(uuid, url):
    result = f"../static/output/{uuid}.jpg"
    timeout = 3
    try:
        print("[.] Begin crawling...")
        page = await browser.newPage()
        await page.goto(url, {
            'timeout': timeout * 1000,
            'waitUntil' : 'networkidle0'
        })
        print("[.] Mobile Rendering complete..")
        # For super extreme browser rendering
        # Credits to tyage for reporting an excellent bug :)
        await page.setViewport({
            'width': 16, # 640//2,
            'height': 16, #1136//2})
        })
        await page.screenshot({'path': result, "type": "jpeg"})
        print(f"[.] Done saving the file. ({uuid})")

    except Exception as e:
        print(f"[!!] {e}")
    finally:
        await page.close()

async def main(loop):
    global browser
    while True:
        try:
            print("[+] Handler has begun..")
            data = redis.blpop("query")[1].decode().split("/")
            if data:
                uuid, url = data[0], '/'.join(data[1:])
                print(f"[*] Got a new request: {uuid} => {url}")
                if not browser:
                    browser = await init_browser()
                _task = await asyncio.wait_for(render(uuid, url), timeout=5)

        except Exception as e:
            print(f"[!] {e}")

        finally:
            await asyncio.sleep(0.01)

if __name__ == "__main__":
    loop = asyncio.get_event_loop()
    loop.run_until_complete(main(loop))
