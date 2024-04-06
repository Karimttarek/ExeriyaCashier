import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/views/**/*.blade.php',
        // PowerGridLivewire
        './app/Http/Livewire/**/*Table.php',
        './vendor/power-components/livewire-powergrid/resources/views/**/*.php',
        './vendor/power-components/livewire-powergrid/src/Themes/Tailwind.php',
        // wireUI
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php'
    ],

    theme: {
        fontFamily: {
            sans: ['Figtree', ...defaultTheme.fontFamily.sans],
        },
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // dark: {
                //     'eval-0': '#151823',
                //     'eval-1': '#1f2937',
                //     'eval-2': '#2A2F42',
                //     'eval-3': '#2C3142',
                // },
            },
        },
    },
    presets: [
        require("./vendor/power-components/livewire-powergrid/tailwind.config.js"),
        require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    plugins: [
        forms,
        require("@tailwindcss/forms")({
          strategy: 'class',
        }),
      ],
    darkMode: 'class',
};
