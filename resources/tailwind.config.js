/** @type {import('tailwindcss').Config} */
const defaultConfig = require('shadcn/ui/tailwind.config');

module.exports = {
  ...defaultConfig,
  content: [
    ...defaultConfig.content,
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    ...defaultConfig.theme,
    extend: {
      ...defaultConfig.theme.extend,
      colors: {
        ...defaultConfig.theme.extend.colors,
        'bordo-dark': '#4B001E',
        'bordo-hover': '#73002A',
        'gray-light': '#CCCCCC',
        'gray-dark': '#333333',
      },
      fontFamily: {
        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
      },
      animation: {
        ...defaultConfig.theme.extend.animation,
        'fade-in': 'fadeIn 0.6s ease-out',
      },
      keyframes: {
        ...defaultConfig.theme.extend.keyframes,
        fadeIn: {
          '0%': {
            opacity: '0',
            transform: 'translateY(20px)',
          },
          '100%': {
            opacity: '1',
            transform: 'translateY(0)',
          },
        },
      },
      borderRadius: {
        lg: "var(--radius)",
        md: "calc(var(--radius) - 2px)",
        sm: "calc(var(--radius) - 4px)",
      },
    },
  },
  plugins: [
    ...defaultConfig.plugins,
    require('@tailwindcss/forms'),
    require("tailwindcss-animate"),
  ],
}