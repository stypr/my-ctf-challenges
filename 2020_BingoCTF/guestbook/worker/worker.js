const fs = require('fs')
const md5 = require('md5');

const puppeteer = require('puppeteer');
const Redis = require('ioredis');
const connection = new Redis(6379, 'redis');

const admin_username = "admin";
const admin_password = "__4DM1N_P4SSW0RD__";

const browser_option = {
    executablePath: 'google-chrome-unstable',
    headless: true,
    args: [
        '--no-sandbox',
        '--disable-background-networking',
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
    ],
};

let browser = undefined;

const crawl = async (url) => {
    console.log(`[+] Query! (${url})`);
    const page = await browser.newPage();
    try {
        await page.goto(url, {
            waitUntil: 'networkidle0',
            timeout: 5 * 1000,
        });
        // await page.on("console", msg => { console.log(msg.text()); });
        // await page.waitFor('#answer');
        // await page.type('#answer', 'ㄴ Thanks for messaging! I am really appreciated! :)');
        // await page.waitFor('#answer-button');
        // await Promise.all([
        //    page.$eval('#answer-button', elem => elem.click()),
        //    page.waitForNavigation()
        //]);
        await page.waitForNavigation({timeout: 5000});
    } catch (err){
        console.log(err);
    }
    await page.close();
    console.log(`[+] Done! (${url})`)
};

const init = async () => {
    const browser = await puppeteer.launch(browser_option);
    const page = await browser.newPage();
    console.log(`[+] Setting up...`);
    try {
        await page.goto('http://public/');
        await page.waitFor('#username');
        await page.type('#username', admin_username);
        await page.waitFor('#password');
        await page.type('#password', admin_password);
        await page.waitFor('#login-button');
        await Promise.all([
            page.$eval('#login-button', elem => elem.click()),
            page.waitForNavigation()
        ]);
        const body = await page.evaluate(() => document.body.innerHTML);
        if (!body.includes('Answer')){
            throw Error(`Login failed at ${page.url()}.`);
        }
        console.log(`[+] Setup done!`);
    } catch (err) {
        console.log(`[-] Error while setting up :(`);
        console.log(err);
        const body = await page.evaluate(() => document.body.innerHTML);
        console.log(`body: ${body}`);
    }
    try{
        await page.close();
    } catch (err) {
        console.log(err);
    }
    return browser;
};

function handle(){
    console.log("[+] handle");
    connection.blpop("query", 0, async function(err, message) {
        if (browser === undefined) browser = await init();
        await crawl("http://public/index.php?id=" + message[1]);
        setTimeout(handle, 10); // handle next
    });
}
handle();
