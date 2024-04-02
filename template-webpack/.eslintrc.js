module.exports = {
    env: {
      browser: true,
      es2021: true,
    },
    extends: ['eslint:recommended'],
    parserOptions: {
      ecmaVersion: 12,
      sourceType: 'module',
    },
    plugins: ['import'],
    rules: {
      // Add custom rules as needed
    },
  };