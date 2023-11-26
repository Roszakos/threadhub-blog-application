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
            keyframes: {
                'expand-to-full': {
                    'from': {
                        height: 0
                    },
                    'to': {
                        height: '100%',
                        borderTopRightRadius: '1rem',
                        borderTopLeftRadius: '1rem',
                        
                    }
                },
                'shrink-from-full': {
                    'from': {
                        height: '100%',
                        borderTopRightRadius: '1rem',
                        borderTopLeftRadius: '1rem',
                    },
                    'to': {
                        borderTopRightRadius: '0rem',
                        borderTopLeftRadius: '0rem',
                        height: 0
                    }
                },
                'opacity-to-100': {
                    '0%': {
                        opacity: 0,
                    },
                    '50%' : {
                        opacity: 0,
                    },
                    '100%': {
                        opacity: 100,
                    }
                },
                'opacity-to-0': {
                    '0%': {
                        opacity: 100,
                    },
                    '50%' : {
                        opacity: 0,
                    },
                    '100%': {
                        opacity: 0,
                    }
                }
            },
            animation: {
                'expand-to-full': "expand-to-full 0.4s ease-in-out both",
                'shrink-from-full': "shrink-from-full 0.4s ease-in-out both",
                'opacity-to-100': "opacity-to-100 0.4s ease-in-out both",
                'opacity-to-0': "opacity-to-0 0.4s ease-in-out both"
            }
        },
    },

    plugins: [forms],
};
