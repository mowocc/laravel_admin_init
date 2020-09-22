let mix = require('laravel-mix');

// 合并任何 Webpack 配置以覆盖默认配置
mix.webpackConfig({
    output: {
        chunkFilename: "js/pages/[id].js?id=[chunkhash]"
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources'),
            '@js': path.resolve(__dirname, 'resources/js'),
            '@img': path.resolve(__dirname, 'resources/img'),
            '@sass': path.resolve(__dirname, 'resources/sass'),
        }
    }
});

// 引入全局 scss 变量，在 vue 文件内使用
mix.options({
    // extractVueStyles: true,
    extractVueStyles: 'public/css/vue-styles.css',
    globalVueStyles: 'resources/sass/_variables.scss'
});


mix.copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/bootstrap.css')
    .copy('node_modules/bootstrap/dist/js/bootstrap.min.js', 'public/js/bootstrap.js')
    .copy('node_modules/jquery/dist/jquery.min.js', 'public/js/jquery.js')
    .copy('node_modules/axios/dist/axios.min.js', 'public/js/axios.js');


// img 图片资源目录复制
mix.copyDirectory('resources/img', 'public/img');


// 编译 JavaScript 和 Sass
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');


// 运行 npm run prod 时版本化控制
if (mix.inProduction()) {
    mix.version();
}

