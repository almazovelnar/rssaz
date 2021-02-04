const fs = require("fs");
const path = require("path");
const webpack = require("webpack");
const HtmlWebpackPlugin = require("html-webpack-plugin");
const HtmlBeautifyPlugin = require("html-beautify-webpack-plugin");
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin");
const BundleAnalyzerPlugin = require("webpack-bundle-analyzer").BundleAnalyzerPlugin;

const mode = "development";

function generateHtmlWebpackPlugins(templatesDir) {
    const templateFiles = fs.readdirSync(path.join(__dirname, templatesDir));

    return templateFiles.map(item => {
        const fileParts = item.split(".");
        const name = fileParts[0];
        const extension = fileParts[1];

        return new HtmlWebpackPlugin({
            filename: `${name}.html`,
            template: path.join(
                __dirname,
                `${templatesDir}/${name}.${extension}`
            )
        });
    });
}

const htmlPlugins = generateHtmlWebpackPlugins("./src/pug/pages");

const htmlBeautify = [
    new HtmlBeautifyPlugin({
        config: {
            html: {
                end_with_newline: true,
                indent_size: 4,
                indent_with_tabs: true,
                indent_inner_html: true,
                preserve_newlines: true,
                unformatted: ["p", "i", "b", "span"]
            }
        }
    })
];

const entry = {
    common: "./src/js/common.js"
};

const output = {
    path: path.join(__dirname, "dist"),
    filename: "js/[name].js"
};

const externals = {
    jquery: 'jQuery'
};

const optimization = {
    namedChunks: true,
    splitChunks: {
        cacheGroups: {
            jquery: {
                test: /[\\/]node_modules[\\/](jquery)[\\/]/,
                name: "jquery",
                chunks: "all"
            },
            vendorGroup: {
                test: /[\\/]node_modules[\\/]((?!(jquery)).*).*\.js$/,
                name: "group-name",
                chunks: 'all'
            }
        }
    }
};

const rules = [
    {
        test: /\.pug$/,
        loader: "pug-loader",
        options: {
            pretty: true
        }
    },
    {
        test: /\.(css|scss)$/,
        use: ExtractTextPlugin.extract({
            publicPath: "../",
            fallback: "style-loader",
            use: ["css-loader", "sass-loader"]
        })
    },
    {
        test: /\.m?js$/,
        exclude: /(node_modules)/,
        use: {
            loader: "babel-loader",
            options: {
                presets: ["@babel/preset-env"]
            }
        }
    },
    {
        test: /\.(jpg|jpeg|png|svg|ico)$/i,
        use: [
            {
                loader: "file-loader",
                options: {
                    name: "[path][name].[ext]",
                    outputPath: file => {
                        return file.split("src/")[1];
                    }
                }
            }
        ]
    },
    {
        test: /\.(woff(2)?|ttf|eot|otf)$/i,
        loader: "file-loader",
        options: {
            outputPath: "fonts",
            publicPath: "../fonts",
            name: "[name].[ext]"
        }
    }
];

const plugins = [
    new ExtractTextPlugin({
        filename: "css/style.css"
    }),
    new webpack.ProvidePlugin({
        $: "jquery",
        jQuery: "jquery",
        "window.jQuery": "jquery"
    }),
    new BrowserSyncPlugin({
        host: "localhost",
        port: 8080,
        server: { baseDir: ["dist"] }
    }),
    // mode == "development" ? new BundleAnalyzerPlugin() : null
].concat(htmlPlugins, htmlBeautify);

module.exports = {
    entry: entry,
    output: output,
    externals: externals,
    module: {
        rules: rules
    },
    plugins: plugins
};
