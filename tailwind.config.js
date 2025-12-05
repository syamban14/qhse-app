import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import colors from 'tailwindcss/colors';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/livewire/livewire/src/Features/SupportPagination/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'qhse-primary': colors.blue[700],      // 30% - Secondary Color (Corporate Blue)
                'qhse-secondary': colors.emerald[500], // 10% - Accent for Success
                'qhse-accent': colors.orange[500],     // 10% - Main CTA Accent
                'qhse-danger': colors.red[600],        // 10% - Accent for Danger
                'qhse-neutral-light': colors.slate[100], // 60% - Dominant Light BG
                'qhse-neutral-dark': colors.gray[900],   // 60% - Dominant Dark BG
            },
        },
    },

    plugins: [forms],
};
