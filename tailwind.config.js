/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#2c92e5',
        secondary: '#0059a2',
      }
    },
  },
  plugins: [],
}
