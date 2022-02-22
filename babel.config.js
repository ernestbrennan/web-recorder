module.exports = {
  presets: [
    [
      '@babel/preset-env',
      // Config for @babel/preset-env
      {
        // Example: Always transpile optional chaining/nullish coalescing
        // include: [
        //   /(optional-chaining|nullish-coalescing)/
        // ],
      },
    ],
    '@babel/preset-typescript',
  ]
};
