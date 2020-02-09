const webpack = require("webpack");
const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config");
const postcssPresetEnv = require("postcss-preset-env");

module.exports = {
  ...defaultConfig,
  module: {
    ...defaultConfig.module,
    rules: [
      ...defaultConfig.module.rules,
      {
        test: /\.(sc|sa|c)ss$/,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "[name].css"
            }
          },
          {
            loader: "extract-loader"
          },
          {
            loader: "css-loader"
          },
          {
            loader: "sass-loader"
          },
          {
            loader: "postcss-loader",
            options: {
              ident: "postcss",
              plugins: () => [postcssPresetEnv(/* pluginOptions */)]
            }
          }
        ]
      }
    ]
  },
  plugins: [...defaultConfig.plugins]
};
