import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'kiddify-blue': '#5A9CB5',
                'kiddify-yellow': '#FACE68',
                'kiddify-orange': '#FAAC68',
                'kiddify-red': '#FA6868',
            },
        },
    },

    plugins: [forms],
};
