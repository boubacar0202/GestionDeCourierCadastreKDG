import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import scrollbar from 'tailwind-scrollbar';


/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{vue,js,ts,jsx,tsx}',
    ],

     theme: {
        extend: {
            colors: {
                primary: '#6d3500', // Définir primary comme    #6d3500
                "primary-dark": "#A35200", //   #A35200
                "primary-txt": "#592D00",   // #592D00
                "primary-only": "#E6C5A2", // #E6C5A2
                "primary-menu": "#C7812E", //  #C7812E
                "primary-form": "#ffe5c5", //  #ffe5c5
                "primary-layout": "#fff7ee", //#fff7ee
            },
            // Ajoute la personnalisation de la scrollbar
            scrollbar: ['rounded'],
        },
    },

    plugins: [
        scrollbar,
    ],

   
    // darkMode: 'class', // ou 'media' selon le comportement souhaité
    // theme: {
    //     extend: {},
    // },
    // plugins: [],
      
      
};




