import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
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
                blue: {
                    50: '#f0f7fc',
                    100: '#e1eff8',
                    200: '#bce0f6',
                    300: '#82c4ee',
                    400: '#40a3e3',
                    500: '#0388d1',
                    600: '#026ea9', // Primary Brand Blue - Premium Deep Navy
                    700: '#015786', // Hover Brand Blue
                    800: '#014368',
                    900: '#0a2540',
                    950: '#061329',
                },
                emerald: {
                   50: '#effaf5',
                   100: '#d7f3e5',
                   200: '#b0e7cb',
                   300: '#7ad5a9',
                   400: '#46bd86',
                   500: '#10b981',
                   600: '#059669', // Haramain Emerald Green
                   700: '#047857',
                   800: '#065f46',
                   900: '#064e3b',
                },
                amber: {
                   50: '#fefbf3',
                   100: '#fdf5e2',
                   200: '#fbe7b9',
                   300: '#f8d287',
                   400: '#f5b953',
                   500: '#f59e0b',
                   600: '#d97706', // Sahara Amber Gold
                   700: '#b45309',
                   800: '#92400e',
                   900: '#78350f',
                },
                stone: {
                   50: '#fafaf9',
                   100: '#f5f5f4',
                   200: '#e7e5e4',
                   300: '#d6d3d1',
                   400: '#a8a29e',
                   500: '#78716c',
                   600: '#57534e',
                   700: '#44403c',
                   800: '#292524',
                   900: '#1c1917',
                   950: '#0c0a09',
                }
            }
        },
    },

    plugins: [forms],
};
