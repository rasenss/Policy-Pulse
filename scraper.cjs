const puppeteer = require('puppeteer-extra');
const StealthPlugin = require('puppeteer-extra-plugin-stealth');
const fs = require('fs');

puppeteer.use(StealthPlugin());

(async () => {
    const targetUrl = process.argv[2]; 
    const TARGET_COUNT = 2000; // Target jumlah tweet
    const userDataDir = './chrome_data'; // Folder penyimpanan login

    const browser = await puppeteer.launch({ 
        headless: false, // Jendela Chrome tampil
        defaultViewport: null,
        userDataDir: userDataDir, // Simpan sesi login
        args: ['--no-sandbox', '--disable-setuid-sandbox', '--start-maximized']
    });
    
    const page = await browser.newPage();
    
    console.log("1. Membuka Target...");
    try {
        await page.goto(targetUrl, { waitUntil: 'domcontentloaded', timeout: 60000 });
    } catch (e) {
        // Ignore timeout
    }

    // --- LOGIKA DETEKSI LOGIN ---
    // Cek apakah kita dilempar ke halaman login atau tidak bisa liat konten
    const currentUrl = page.url();
    const isLoginPage = currentUrl.includes('login') || currentUrl.includes('i/flow/login');
    const hasLoginButton = await page.$('a[href="/login"]');

    if (isLoginPage || hasLoginButton) {
        console.log("------------------------------------------------");
        console.log("⚠️  ROBOT MENDETEKSI BELUM LOGIN");
        console.log("👉  SILAKAN LOGIN MANUAL DI JENDELA CHROME SEKARANG");
        console.log("👉  Robot akan menunggu Anda sampai masuk 'Home'...");
        console.log("------------------------------------------------");

        // Tunggu sampai URL berubah menjadi 'home' (Tanda sukses login)
        try {
            await page.waitForFunction(() => window.location.href.includes('/home'), { timeout: 300000 }); // Tunggu 5 menit
            console.log("✅  LOGIN TERDETEKSI!");
            
            // TUNGGU SEBENTAR BIAR COOKIES TERSIMPAN
            await new Promise(r => setTimeout(r, 3000));

            // --- INI PERBAIKANNYA: BALIK LAGI KE TARGET ---
            console.log("🔄  Mengalihkan dari FYP kembali ke Tweet Target...");
            await page.goto(targetUrl, { waitUntil: 'networkidle2', timeout: 60000 });
            
        } catch (e) {
            console.log("❌  Waktu habis/Gagal login. Keluar.");
            await browser.close();
            return;
        }
    } else {
        console.log("✅  Sesi Login Lama Terdeteksi (Aman).");
    }

    // --- PASTIKAN KITA ADA DI HALAMAN TWEET, BUKAN HOME ---
    if (page.url().includes('/home')) {
         console.log("⚠️  Masih di Home, memaksa masuk ke Target...");
         await page.goto(targetUrl, { waitUntil: 'domcontentloaded' });
    }

    console.log("🚀  MULAI SCROLLING & SCRAPING DATA...");

    // --- LOGIKA SCROLLING ---
    let collectedTweets = new Map();
    let noNewDataCount = 0;
    let scrollAttempts = 0;

    while (collectedTweets.size < TARGET_COUNT && noNewDataCount < 20) {
        
        // Scrape Data
        const newTweets = await page.evaluate(() => {
            const articles = document.querySelectorAll('article');
            const data = [];
            
            articles.forEach(article => {
                try {
                    const timeElement = article.querySelector('time');
                    const textElement = article.querySelector('div[data-testid="tweetText"]');
                    const userElement = article.querySelector('div[data-testid="User-Name"]');
                    
                    // Pastikan ini bukan Tweet Utama (biasanya font lebih besar), tapi replies
                    // Kita ambil semua saja nanti filter di Laravel
                    if (textElement && userElement) {
                        const rawUser = userElement.innerText.split('\n');
                        let screenName = 'anon';
                        if (rawUser.length > 1) screenName = rawUser[1];
                        
                        data.push({
                            id: (timeElement ? timeElement.getAttribute('datetime') : Date.now()) + textElement.innerText.substring(0,10),
                            text: textElement.innerText,
                            user: { screen_name: screenName },
                            created_at: timeElement ? timeElement.getAttribute('datetime') : new Date().toISOString()
                        });
                    }
                } catch (err) {}
            });
            return data;
        });

        // Simpan Unik
        let addedThisRound = 0;
        newTweets.forEach(t => {
            if (!collectedTweets.has(t.id)) {
                collectedTweets.set(t.id, t);
                addedThisRound++;
            }
        });

        if (addedThisRound === 0) noNewDataCount++;
        else noNewDataCount = 0;

        // Scroll
        await page.evaluate('window.scrollTo(0, document.body.scrollHeight)');
        
        // Random Wait (2-4 detik)
        const waitTime = Math.floor(Math.random() * 2000) + 2000;
        await new Promise(resolve => setTimeout(resolve, waitTime));
        
        scrollAttempts++;
        
        // Update status di console node (optional)
        if (scrollAttempts % 5 === 0) {
            // console.log(`   Stats: ${collectedTweets.size} tweets terkumpul...`);
        }
        
        if (scrollAttempts > 400) break; 
    }

    // Output JSON
    const finalData = Array.from(collectedTweets.values());
    console.log(JSON.stringify(finalData));

    await browser.close();
})();