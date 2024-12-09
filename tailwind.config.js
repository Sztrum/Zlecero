/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "app/V1/Core/UI/Http/Resources/assets/js/**/*.{js,ts}",
        "app/V1/Core/UI/Http/Resources/assets/scss/**/*.scss",
        "storage/framework/views/*.php",
        "app/V1/**/*.php",
    ],
    theme: {
        extend: {
            screens: {
                xs: "420px",
                sm: "580px",
                md: "800px",
                lg: "1024px",
                half_xl: "1280px",
                xl: "1366px",
                "2xl": "1440px",
                "3xl": "1570px",
                "4xl": "2500px",
            },
            "colors": {
                "neutral": {
                    "0": "#ffffff",
                    "100": "#f6f8f9",
                    "150": "#eaedef",
                    "200": "#dce1e4",
                    "300": "#c0cbcf",
                    "400": "#a5b4ba",
                    "500": "#8ea4a9",
                    "600": "#667f87",
                    "700": "#4a5e6a",
                    "800": "#32454d",
                    "900": "#213134",
                    "1000": "#182326"
                },
                "primary": {
                    "100": "#eef6ff",
                    "200": "#cbe4ff",
                    "500": "#7fbcff",
                    "600": "#2c8cf4",
                    "700": "#0076f6"
                },
                "success": {
                    "100": "#cbffe7",
                    "200": "#a5eccb",
                    "500": "#35df91",
                    "600": "#1eb972",
                    "700": "#178f58"
                },
                "danger": {
                    "100": "#fcecea",
                    "200": "#fac2b6",
                    "500": "#f47960",
                    "600": "#e94e30",
                    "700": "#d52f0e"
                },
                "warning": {
                    "100": "#fff9e9",
                    "200": "#fee7b9",
                    "500": "#fac762",
                    "600": "#f0ae2c",
                    "700": "#e39707"
                },
                "primary-button": {
                    "0": "#ffffff",
                    "500": "#7fbcff",
                    "600": "#2c8cf4"
                }
            },
        },
        fontFamily: {
            primary: ["Poppins", "sans-serif"],
        },
        container: {
            center: true,
        },
        "boxShadow": {
            "drop-shadow": "0px 1px 4px 0px rgba(33,33,52,0.1)",
            "table-container": "0px 1px 4px 0px rgba(33,33,52,0.1)"
        },
    },
    plugins: [
        require("@tailwindcss/typography"),
        require("tailwind-scrollbar")({ nocompatible: true }),
    ],
}

