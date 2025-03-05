import {expect, test} from "@playwright/test";


test('has title', async ({ page, baseURL }) => {
    await page.goto(baseURL);

    await expect(page).toHaveTitle(/Same Day Racing/);
});

test('can add team', async ({ page, baseURL }) => {
    await page.goto(baseURL);
    await expect(page).toHaveTitle(/Same Day Racing/);
})
