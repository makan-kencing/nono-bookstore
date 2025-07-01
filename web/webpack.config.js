const path = require('path');

module.exports = {
    entry: './assets/js/index.js',
    module: {
        rules: [
            {
                test: /\.(scss)$/,
                use: [{
                    loader: 'style-loader', // inject CSS to page
                }, {
                    loader: 'css-loader', // translates CSS into CommonJS modules
                }, {
                    loader: 'sass-loader' // compiles Sass to CSS
                }]
            },
        ]
    },
    output: {
        path: path.resolve(__dirname, 'public', 'dist'),
        filename: 'bundle.js',
    }
};