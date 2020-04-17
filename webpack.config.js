const path = require('path');

module.exports = {
    entry: {
        main: './src/js/main.js'
    },
    output: {
        filename: '[name].js',
        // publicPath: '/js/', // public path is defined dynamically based on the installation folder
        path: path.resolve(__dirname, 'assets/js')
    },
    devtool: 'source-map',
    plugins: [],
    module: {
        rules: [
            {
                test: /\.js?/,
                use: 'babel-loader',
                exclude: /node_modules/
            }
            /*
            {
                test: require.resolve('jquery'),
                use: [
                    {
                        loader: 'expose-loader',
                        query: '$'
                    },
                    {
                        loader: 'expose-loader',
                        query: 'jQuery'
                    }
                ]
            }

             */
        ]
    }
};
