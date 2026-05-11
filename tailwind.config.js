/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        navy:    { DEFAULT: '#980D0D', light: '#B21A1A', dark: '#7A0A0A' },
        primary: { DEFAULT: '#980D0D', light: '#B21A1A', dark: '#7A0A0A' },
        amber:   { DEFAULT: '#F39C12', light: '#F5A623', dark: '#D68910' },
        success: '#27AE60',
        danger:  '#E74C3C',
        surface: '#FFFFFF',
        muted:   '#566573',
      },
      fontFamily: {
        sans: ['Inter', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
