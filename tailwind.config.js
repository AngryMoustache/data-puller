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
        'super-dark': 'var(--color-primary-super-dark)',
        background: 'var(--color-background)',
        text: 'var(--color-text)',
        surface: '#151515',
        'surface-light': 'var(--color-surface-light)',
        'surface-dark': 'var(--color-surface-dark)',
        error: 'var(--color-error)',
        gradient: 'linear-gradient(120deg, var(--color-primary-light), var(--color-primary-dark))'
      }
    },
  },
  plugins: [],
}
