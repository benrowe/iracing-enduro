import {expect, test} from "@playwright/test";


test('has title', async ({ page }) => {
    await page.goto(page.url());

    await expect(page).toHaveTitle(/Same Day Racing/);
});

test('can add team', async ({ page }) => {
    await page.goto(page.url());
    await expect(page).toHaveTitle(/Same Day Racing/);
})
