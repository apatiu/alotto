const path = require('path');
const webpack = require('webpack');

module.exports = {
    plugins:[
        new webpack.DefinePlugin({
            __VUE_OPTIONS_API__: true,
            __VUE_PROD_DEVTOOLS__: false
        })
    ],
    resolve: {
        alias: {
            '@': path.resolve('resources/js'),
        },
    },

};
