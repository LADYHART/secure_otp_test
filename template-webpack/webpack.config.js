const path = require("path");

const HtmlWebpackPlugin = require("html-webpack-plugin");
const HtmlMinimizerPlugin = require("html-minimizer-webpack-plugin");

module.exports = {
  entry: "./src/index.js",
  output: {
    filename: "bundle.js",
    path: path.resolve(__dirname, "dist"),
  },
  devServer: {
    static: {
      directory: path.join(__dirname, "dist"), // Serve content from the 'dist' directory
    },
    compress: true, // Enable gzip compression
    port: 9000, // Specify port number
    hot: true, // Enable Hot Module Replacement (HMR)
  },
  plugins: [
    new HtmlWebpackPlugin({
      template: "./src/index.html", // Path to your HTML template
      minify: true, // Enable minification
    }),
  ],
  optimization: {
    minimize: true,
    minimizer: [new HtmlMinimizerPlugin()],
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: ["babel-loader", "eslint-loader"],
      },
      {
        test: /\.json$/,
        loader: 'json-loader',
        type: 'javascript/auto'
      },
    ],
  },
};
