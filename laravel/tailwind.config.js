/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  darkMode: "class",
  theme: {
    extend: {
      colors: {
        "primary": "#1e3b8a", // Royal Blue
        "background-light": "#f6f6f8",
        "background-dark": "#121620",
        "gold": "#d97706", // Amber-600 for Gold text
        "whatsapp": "#25D366",
        "royal-blue": "#1e3a8a",
        "royal-blue-dark": "#172554",
        "primary-admin": "#f49e0b",
        "primary-admin-dark": "#d98b09"
      },
      fontFamily: {
        "display": ["Inter", "sans-serif"],
        "body": ["Inter", "sans-serif"]
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    //require('@tailwindcss/container-queries')
  ],
}

