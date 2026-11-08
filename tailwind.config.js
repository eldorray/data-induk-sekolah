import defaultTheme from 'tailwindcss/defaultTheme';
import colors from 'tailwindcss/colors';
import forms from '@tailwindcss/forms';

// Hijau madrasah: satu aksen untuk seluruh aplikasi.
// ponytail: blue/indigo dipetakan ke hijau supaya ~30 view Livewire lama ikut tema tanpa diedit satu-satu.
const madrasah = {
    50: '#f1f6f3',
    100: '#e3ede6',
    200: '#c5dbcd',
    300: '#9cc1aa',
    400: '#6a9d80',
    500: '#3f7c5d',
    600: '#1d5c45',
    700: '#164a37',
    800: '#123c2d',
    900: '#0f3126',
    950: '#081c15',
};

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                gray: colors.stone,
                blue: madrasah,
                indigo: madrasah,
                madrasah,
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                serif: ['Newsreader', ...defaultTheme.fontFamily.serif],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [forms],
};
