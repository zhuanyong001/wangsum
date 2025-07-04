const { defineConfig } = require('@vue/cli-service');
const path = require('path');

// 基本路径设置
const BASE_URL = process.env.NODE_ENV !== 'production' ? '' : '/';

module.exports = defineConfig({
  transpileDependencies: true,
  publicPath: BASE_URL,

  // 配置别名
  configureWebpack: {
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src') // 设置 @ 为 src 目录
      }
    }
  },

  // 配置全局 SCSS
  css: {
    loaderOptions: {
      scss: {
        additionalData: `@import "@/styles/index.scss";`
      }
    }
  }
});
