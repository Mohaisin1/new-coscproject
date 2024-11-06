/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./dist/*.{html,js}"],
  theme: {
    extend: {
      colors: {
        "main-purple": "#9253FE",
        "main-blue": "#858FFF",
        "main-light-purple": "#EBDFFF",
        "nav-purple": "#CDAFFF",
        "main-purple-shade": "#8849f4",
      },
      fontFamily: {
        roboto: ["Roboto", "sans-serif"],
      },
    },
  },
  plugins: [],
};
