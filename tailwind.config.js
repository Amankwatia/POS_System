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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Primary (Brand / Actions)
                primary: {
                    DEFAULT: '#166534',
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#166534',
                    800: '#14532d',
                    900: '#052e16',
                },
                // Secondary (Highlights / Positive)
                secondary: {
                    DEFAULT: '#22C55E',
                    light: '#4ade80',
                    dark: '#16a34a',
                },
                // Backgrounds
                surface: {
                    DEFAULT: '#F9FAFB',
                    card: '#FFFFFF',
                },
                // Text colors
                content: {
                    DEFAULT: '#111827',
                    secondary: '#374151',
                    muted: '#6B7280',
                },
                // Status colors
                success: {
                    DEFAULT: '#22C55E',
                    light: '#dcfce7',
                    dark: '#166534',
                },
                warning: {
                    DEFAULT: '#EAB308',
                    light: '#fef9c3',
                    dark: '#a16207',
                },
                danger: {
                    DEFAULT: '#DC2626',
                    light: '#fee2e2',
                    dark: '#991b1b',
                },
                info: {
                    DEFAULT: '#3B82F6',
                    light: '#dbeafe',
                    dark: '#1d4ed8',
                },
            },
            boxShadow: {
                'card': '0 1px 3px 0 rgb(0 0 0 / 0.04), 0 1px 2px -1px rgb(0 0 0 / 0.04)',
                'card-hover': '0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05)',
            },
            spacing: {
                'sidebar': '260px',
                'sidebar-collapsed': '80px',
                'topbar': '64px',
            },
        },
    },

    plugins: [forms],
};
