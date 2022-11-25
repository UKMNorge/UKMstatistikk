const path = require('path');

const config = {
    entry: './src/index.js',
    output: {
        filename: 'main.js',
        path: path.resolve(__dirname, 'dist'),
    },
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        }
    }
};


// UKM_KA: We use Vagrant and therefore watching does not work without activating pool
config.watchOptions = {
    poll: true,
};

config.mode = 'development';

module.exports = config;