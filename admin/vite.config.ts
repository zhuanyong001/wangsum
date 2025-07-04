import { defineConfig, loadEnv } from 'vite';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import Components from 'unplugin-vue-components/vite';
import AutoImport from 'unplugin-auto-import/vite';
import { AntDesignVueResolver } from 'unplugin-vue-components/resolvers';
import { AntdvLessPlugin, AntdvModifyVars } from 'stepin/lib/style/plugins';
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers';
const timestamp = new Date().getTime();
const prodRollupOptions = {
  output: {
    chunkFileNames: (chunk) => {
      return 'assets/' + chunk.name + '.[hash]' + '.' + timestamp + '.js';
    },
    assetFileNames: (asset) => {
      const name = asset.name;
      if (name && (name.endsWith('.css') || name.endsWith('.js'))) {
        const names = name.split('.');
        const extname = names.splice(names.length - 1, 1)[0];
        return `assets/${names.join('.')}.[hash].${timestamp}.${extname}`;
      }
      return 'assets/' + asset.name;
    },
  },
};
// vite 配置
export default ({ command, mode }) => {
  // 获取环境变量
  const env = loadEnv(mode, process.cwd());
  console.log(mode);
  // env.VITE_API_BASE_URL
  return defineConfig({
    server: {
      port: 3000,
      proxy: {
        '/admin/adminapi': {
          //target: 'http://103.234.54.91:8686',
          target: 'http://127.0.0.1:8000',
          ws: true,
          changeOrigin: true,
        },
      },
      hmr: true,
      host: '0.0.0.0',
    },

    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'src'),
        '#': path.resolve(__dirname, 'src/types'),
      },
    },
    esbuild: {
      jsxFactory: 'h',
      jsxFragment: 'Fragment',
    },
    build: {
      sourcemap: false,
      chunkSizeWarningLimit: 2048,
      rollupOptions: mode === 'production' ? prodRollupOptions : {},
    },
    plugins: [
      vue({
        template: {
          transformAssetUrls: {
            img: ['src'],
            'a-avatar': ['src'],
            'stepin-view': ['logo-src', 'presetThemeList'],
            'a-card': ['cover'],
          },
        },
      }),
      Components({
        resolvers: [
          AntDesignVueResolver({ importStyle: mode === 'development' ? false : 'less' }),
          ElementPlusResolver(),
        ],
      }),
      AutoImport({
        resolvers: [ElementPlusResolver()],
        eslintrc: {
          enabled: true,
        },
        include: [/\.[tj]sx?$/, /\.vue$/, /\.vue\?vue/],
        imports: [
          'vue',
          'vue-router',
          'vitest',
          '@vueuse/core',

          'pinia',
          {
            '@/utils/useHttp': ['usePost', 'useGet', 'useHttp'],
            dayjs: [['default', 'dayjs']],
            'ant-design-vue': ['message', 'notification', 'Modal'],
          },
        ],
        dts: true,
      }),
    ],
    css: {
      preprocessorOptions: {
        less: {
          plugins: [AntdvLessPlugin],
          modifyVars: AntdvModifyVars,
          javascriptEnabled: true,
        },
      },
    },
    base: env.VITE_BASE_URL,
  });
};
