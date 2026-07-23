import puppeteer from 'puppeteer';
import path from 'path';

(async () => {
  const browser = await puppeteer.launch({
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--use-fake-ui-for-media-stream']
  });
  const page = await browser.newPage();
  
  // Set viewport to a realistic desktop size
  await page.setViewport({ width: 1280, height: 800 });
  
  console.log('Visiting test login route to authenticate as patient...');
  await page.goto('http://127.0.0.1:8000/test-login/2/6', { waitUntil: 'networkidle0' });
  
  console.log('Taking screenshot of consent form...');
  await page.screenshot({ path: 'consent_form.png', fullPage: true });

  // Wait for the checkbox to be visible and click it
  try {
      console.log('Looking for consent checkbox...');
      await page.waitForSelector('input[name="consent"]', { timeout: 5000 });
      await page.click('input[name="consent"]');
      
      console.log('Submitting consent form...');
      await Promise.all([
          page.waitForNavigation({ waitUntil: 'networkidle0' }),
          page.click('button[type="submit"]')
      ]);
      
      console.log('Taking screenshot of Jitsi room...');
      // Wait for Jitsi iframe to load
      await page.waitForSelector('#jitsi-container iframe', { timeout: 15000 });
      // Give Jitsi a few seconds to fully initialize the UI
      await new Promise(r => setTimeout(r, 5000));
      
      await page.screenshot({ path: 'jitsi_room.png', fullPage: true });
      console.log('Test completed successfully!');
  } catch (err) {
      console.error('Error during test execution:', err);
      // Take an error screenshot
      await page.screenshot({ path: 'error_state.png', fullPage: true });
  }

  await browser.close();
})();
