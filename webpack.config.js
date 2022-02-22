const path = require('path');

function resolve(dir) {
    return path.join(
        __dirname,
        '/resources/js',
        dir
    );
}

module.exports = {
    resolve: {
        extensions: ['.js', '.vue', '.json'],
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            '@js': path.join(__dirname, '/resources/js'),
            '@sass': path.join(__dirname, '/resources/sass'),
        },
    },
};
