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
                'qhse-primary': '#1a73e8', // A strong blue
                'qhse-secondary': '#34a853', // A vibrant green
                'qhse-accent': '#fbbc05', // A warning yellow
                'qhse-danger': '#ea4335', // A red for critical alerts
                'qhse-neutral-light': '#f8f9fa',
                'qhse-neutral-dark': '#343a40',
            },
        },
    },

    plugins: [forms],
};
