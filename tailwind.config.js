import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import scrollbar from 'tailwind-scrollbar';


/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

     theme: {
        extend: {
            colors: {
                primary: '#6d3500', // Définir primary comme    #6d3500
                "primary-dark": "#bd7b00", //   #bd7b00
            },
            // Ajoute la personnalisation de la scrollbar
            scrollbar: ['rounded'],
        },
    },

    plugins: [
        require('tailwind-scrollbar')
    ],
};




