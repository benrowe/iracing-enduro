import {defineConfig} from '@playwright/test';

export default defineConfig({
    webServer: {
        command: 'php artisan serve --host=0.0.0.0 --port=8000',
        port: 8000,
        reuseExistingServer: !process.env.CI,
        timeout: 120 * 1000,
    },
    use: {
        baseURL: 'http://localhost:8000',
        headless: true,
    },
});
