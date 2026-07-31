import defaultTheme from 'tailwindcss/defaultTheme';

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
                navy: {
                    DEFAULT: '#0B1F3D',
                    light: '#12305C',
                },
                gold: {
                    DEFAULT: '#B8912F',
                    light: '#D9B65C',
                },
                health: {
                    DEFAULT: '#1F7A4D',
                    dark: '#155C39',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [],
};