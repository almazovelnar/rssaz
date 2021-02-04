const path = require("path");
const webpack = require("webpack");
const ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
    entry: {
        index: "./js/index.js",
        generated: "./js/generated/index.js"
    },
    output: {
        path: path.join(__dirname, "../web/"),
        filename: "js/[name].js"
    },
    externals: {
        jquery: 'jQuery'
    },
    module: {
        rules: [
            {
                test: /\.m?js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ["@babel/preset-env"]
                    }
                }
            },
            {
                test: /\.css$/,
                use: ExtractTextPlugin.extract({
                    publicPath: "../web/",
                    fallback: "style-loader",
                    use: ["css-loader"]
                })
            },
        ]
    },
    plugins: [
        new webpack.IgnorePlugin(/^\.\/locale$/, /moment$/),
        new ExtractTextPlugin({ filename: "css/vendor.css" }),
    ]
};
