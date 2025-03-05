import {defineConfig} from '@playwright/test';

export default defineConfig({
    testDir: './tests/E2E',
    webServer: {
        command: 'php artisan serve --host=0.0.0.0 --port=8000',
        // command: 'make up',
        stdout: "pipe",
        url: "http://localhost:8000",
        // port: process.env.PORT || 8000,
        reuseExistingServer: !process.env.CI,
        timeout: 120 * 1000,
    },
    use: {
        baseURL: 'http://localhost:8000',
        headless: true,
    },
});
