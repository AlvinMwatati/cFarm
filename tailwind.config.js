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
                sans: ['DM Sans', ...defaultTheme.fontFamily.sans],
                display: ['Playfair Display', 'Georgia', 'serif'],
                body: ['DM Sans', 'sans-serif'],
                mono: ['DM Mono', 'monospace'],
            },
            colors: {
                primary: { DEFAULT: '#C1440E', dark: '#8B3008', light: '#E8693A', muted: '#F4DDD3' },
                secondary: { DEFAULT: '#2D5016', dark: '#1A2F0D', light: '#4A7A28', muted: '#D6E8C4' },
                accent: { DEFAULT: '#D4A017', light: '#F5E6A3' },
                soil: '#3D2B1F',
                bark: '#6B4C35',
                sand: '#F5EFE6',
                parchment: '#FBF7F2',
                stone: '#C4B5A5',
                mist: '#EAE4DC',
                up: '#2D8A4E',
                down: '#C1440E',
                neutral: '#8A7968',
            },
        },
    },

    plugins: [forms],
};
