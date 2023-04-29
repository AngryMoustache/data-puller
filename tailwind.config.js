/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: 'var(--color-primary)',
        light: 'var(--color-primary-light)',
        dark: 'var(--color-primary-dark)',
        'dark-hover': 'var(--color-primary-dark-hover)',
        background: 'var(--color-background)',
        text: 'var(--color-text)',
        border: 'var(--color-border)',
        surface: 'var(--color-surface)',
        error: 'var(--color-error)',
      }
    },
  },
  plugins: [],
}
