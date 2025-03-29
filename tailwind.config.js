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
            screens: {
                xs: '480px',
            },
            keyframes: {
                neonFlicker: {
                    '0%, 19%, 21%, 23%, 25%, 54%, 56%, 100%': {
                        textShadow: '0 0 5px #ff00de, 0 0 10px #ff00de, 0 0 20px #ff00de, 0 0 40px #ff00de'
                    },
                    '20%, 24%, 55%': {
                        textShadow: 'none'
                    }
                },
                arcadeFloat: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' }
                }
            },
            animation: {
                neonFlicker: 'neonFlicker 2s infinite',
                arcadeFloat: 'arcadeFloat 3s ease-in-out infinite'
            },
            boxShadow: {
                'pixel': '0 0 0 3px #fff, 0 0 0 6px #ff00de'
            }
        },
    },

    plugins: [forms],
};
