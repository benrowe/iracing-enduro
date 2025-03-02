import {expect, test} from "@playwright/test";


test('has title', async ({ page }) => {
    await page.goto('http://localhost/');

    // Expect a title "to contain" a substring.
    console.log(await page.title())
    await expect(page).toHaveTitle(/Same Day Racing/);
});

test('can add team', async ({ page }) => {

})
