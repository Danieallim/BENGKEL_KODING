import defaultTheme from "tailwindcss/defaultTheme";
import daisyui from "daisyui";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [daisyui],
    
    // TAMBAHKAN SETTING DAISYUI DI SINI
    daisyui: {
        themes: ["light"], // Memaksa tema light agar tidak bentrok dengan auto dark mode
        base: true,        // Tetap gunakan base styles
        utils: true,       // Tetap gunakan utility classes
        logs: false,       // Opsional: mematikan log daisyui di terminal
    },
};