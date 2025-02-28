const { iconsPlugin, getIconCollections } = require("@egoist/tailwindcss-icons")

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
    ],
    theme: {
        extend: {
            colors: {
                'red': {
                    DEFAULT: '#cc0000',
                },
                'gray': {
                    DEFAULT: '#000000',
                    50: '#CCCCCC',
                    200: '#555555',
                }
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        iconsPlugin({
            collections: getIconCollections(["mdi"]),
        })
    ],
}

