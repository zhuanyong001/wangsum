// vite.config.ts
import { defineConfig, loadEnv } from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/vite@5.3.3_@types+node@20.14.10_sass@1.77.8_terser@5.31.2/node_modules/vite/dist/node/index.js";

// build/index.ts
import path from "path";

// build/scripts/generate-svg-type.ts
import fs from "fs/promises";
var svgIconDir = pathResolve("src/assets/svg-icons");
var svgIconTypeFileURL = pathResolve("types/auto-generate/auto-svg-name.d.ts");
var iconDataFile = pathResolve("src/components/SvgIcon/icons.ts");
async function generateSvgIconType(dir, outputFile) {
  try {
    const iconFullNameList = await fs.readdir(dir);
    const iconNameList = iconFullNameList.map((item) => item.replace(".svg", ""));
    const types = iconNameList.reduce((prev, item, index) => prev += iconNameList.length - 1 === index ? `'${item}'` : `'${item}' | `, "");
    await fs.writeFile(outputFile, `type SvgIconName = ${types}`, { flag: "w" });
    await fs.writeFile(iconDataFile, `export default ${JSON.stringify(iconNameList)} as SvgIconName[]`);
  } catch (error) {
    console.log("error: ", error);
  }
}
generateSvgIconType(svgIconDir, svgIconTypeFileURL);

// build/index.ts
function warpperEnv(env_config) {
  const env = {};
  for (const [key, value] of Object.entries(env_config)) {
    if (["true", "false"].includes(value)) {
      env[key] = value === "true" ? true : false;
    } else if (Number(value)) {
      env[key] = Number(value);
    } else {
      env[key] = value;
    }
  }
  return env;
}
function pathResolve(dir) {
  return path.normalize(path.resolve(process.cwd(), dir));
}

// build/plugins/index.ts
import vue from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/@vitejs+plugin-vue@5.0.5_vite@5.3.3_@types+node@20.14.10_sass@1.77.8_terser@5.31.2__vue@3.4.31_typescript@5.5.3_/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import vueJsx from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/@vitejs+plugin-vue-jsx@3.1.0_vite@5.3.3_@types+node@20.14.10_sass@1.77.8_terser@5.31.2__vue@3.4.31_typescript@5.5.3_/node_modules/@vitejs/plugin-vue-jsx/dist/index.mjs";
import UnoCSS from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/unocss@0.60.3_postcss@5.2.18_rollup@4.18.1_vite@5.3.3_@types+node@20.14.10_sass@1.77.8_terser@5.31.2_/node_modules/unocss/dist/vite.mjs";

// build/plugins/auto-import-plugin.ts
import AutoImport from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/unplugin-auto-import@0.17.8_rollup@4.18.1/node_modules/unplugin-auto-import/dist/vite.js";
import AutoComponents from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/unplugin-vue-components@0.27.0_@babel+parser@7.24.8_rollup@4.18.1_vue@3.4.31_typescript@5.5.3_/node_modules/unplugin-vue-components/dist/vite.js";
import { AntDesignVueResolver } from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/unplugin-vue-components@0.27.0_@babel+parser@7.24.8_rollup@4.18.1_vue@3.4.31_typescript@5.5.3_/node_modules/unplugin-vue-components/dist/resolvers.js";
var registerAutoImport = () => {
  return AutoImport({
    imports: ["vue", "pinia", "vue-router", "vue-i18n"],
    // 自动导入 vue、vue-router、Pinia 相关函数
    dts: "types/auto-generate/auto-import.d.ts",
    dirs: ["src/store/modules", "src/hooks"]
    // 配置其它需要导入的文件目录
  });
};
var registerAutoComponents = () => {
  return AutoComponents({
    resolvers: [AntDesignVueResolver({ importStyle: false })],
    dts: "types/auto-generate/auto-components.d.ts",
    dirs: ["src/components"]
    // 配置其它需要导入的文件目录
  });
};

// build/plugins/compile.html.ts
import dayjs from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/dayjs@1.11.11/node_modules/dayjs/dayjs.min.js";
import { createHtmlPlugin } from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/vite-plugin-html@3.2.2_vite@5.3.3_@types+node@20.14.10_sass@1.77.8_terser@5.31.2_/node_modules/vite-plugin-html/dist/index.mjs";
var registerHtmlPlugin = (viteEnv, isBuild) => {
  const { VITE_APP_TITLE } = viteEnv;
  return createHtmlPlugin({
    // 在这里写 entry 后，你将不需要在 `index.html` 内添加 script 标签，原有标签需要删除
    entry: "src/main.ts",
    // 是否压缩 html
    minify: isBuild,
    // 需要注入 index.html ejs 模版的数据
    inject: {
      data: { VITE_APP_TITLE, buildTime: dayjs().format(`YYYY-MM-DD HH:mm:ss`) }
    }
  });
};

// build/plugins/image.mini.ts
import minipic from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/vite-plugin-imagemin@0.6.1_vite@5.3.3_@types+node@20.14.10_sass@1.77.8_terser@5.31.2_/node_modules/vite-plugin-imagemin/dist/index.mjs";
var registerImageMini = () => {
  return minipic({
    /** 无损压缩配置，无损压缩下图片质量不会变差 */
    optipng: {
      optimizationLevel: 7
    },
    /** 有损压缩配置，有损压缩下图片质量可能会变差 */
    pngquant: {
      quality: [0.8, 0.9],
      // 压缩质量
      speed: 4
      // 压缩速度，范围 0~11
    },
    gifsicle: {
      optimizationLevel: 7,
      interlaced: false
    },
    mozjpeg: {
      quality: 20
      // 压缩质量
    },
    svgo: {
      plugins: [{ name: "removeViewBox" }, { name: "removeEmptyAttrs", active: false }]
    },
    /** 是否禁用 */
    disable: false,
    /** 是否在控制台输出压缩结果 */
    verbose: true
  });
};

// build/plugins/register-svg-icons.ts
import { createSvgIconsPlugin } from "file:///C:/Users/15899/Documents/vscodeSpace/sun/sunCurrency/node_modules/.pnpm/vite-plugin-svg-icons2@1.0.0_vite@5.3.3_@types+node@20.14.10_sass@1.77.8_terser@5.31.2_/node_modules/vite-plugin-svg-icons2/dist/index.mjs";
function registerSvgIcons() {
  return createSvgIconsPlugin({
    iconDirs: [pathResolve("src/assets/svg-icons")],
    // 指定图标文件夹
    symbolId: "icon-[dir]-[name]"
    // 指定symbolId格式
  });
}

// build/plugins/index.ts
function generateVitePlugins(viteEnv, isBuild) {
  const plugins = [];
  plugins.push(vue({ include: [/\.vue$/] }));
  plugins.push(vueJsx());
  plugins.push(registerSvgIcons());
  plugins.push(UnoCSS());
  plugins.push(registerAutoImport());
  plugins.push(registerAutoComponents());
  plugins.push(registerHtmlPlugin(viteEnv, isBuild));
  if (isBuild) {
    plugins.push(registerImageMini());
  }
  return plugins;
}

// vite.config.ts
var root = process.cwd();
var vite_config_default = defineConfig(({ command, mode }) => {
  const VITE_ENV = warpperEnv(loadEnv(mode, root, "VITE_"));
  const isBuild = command === "build";
  return {
    // 部署应用包时的基本 URL
    base: VITE_ENV.VITE_PUBLIC_PATH,
    plugins: [
      generateVitePlugins(VITE_ENV, isBuild)
      // createSvgIconsPlugin({
      //   // 指定需要缓存的图标文件夹
      //   iconDirs: [path.resolve(process.cwd(), 'src/assets/icons')],
      //   // 指定symbolId格式
      //   symbolId: 'icon-[dir]-[name]'
      // })
    ],
    // 安装：style-resources-loader
    // pluginOptions: {
    //   'style-resources-loader': {
    //     preProcessor: 'scss',
    //     // 这三种 patterns 写法都是可以的
    //     // patterns: ["./src/assets/reset1.less", "./src/assets/reset2.less"]
    //     // patterns: "./src/assets/reset.less"
    //     patterns: [
    //       // 两种路径写法都可以，这里的路径不能使用 @ 符号，否则会报错
    //       // path.resolve(__dirname, './src/assets/reset.less')
    //       path.resolve(__dirname, 'src/styles/variables.scss')
    //     ]
    //   }
    // },
    resolve: {
      alias: [
        /** 设置 `@` 指向 `src` 目录 */
        { find: "@", replacement: pathResolve("src") },
        /** 设置 `#` 指向 `types` 目录 */
        { find: "#", replacement: pathResolve("types") }
      ]
    },
    server: {
      /** 设置为 0.0.0.0 或者 true 将监听所有地址，包括局域网和公网地址 */
      host: true,
      cors: true
      /** 反向代理配置（主要是开发时用来解决跨域问题） */
      // proxy: {
      //   // [process.env.VITE_BASE_API]: process.env.VITE_BASE_URL,
      //   '/api': {
      //     target: process.env.VITE_BASE_URL,
      //     changeOrigin: true,
      //     rewrite: (path) => path.replace(/\/api/, '/api'),
      //     bypass(req, res, options) {
      //       // const proxyURL = options.target + options.rewrite(req.url);
      //       // res.setHeader('x-req-proxyURL', proxyURL) // 将真实请求地址设置到响应头中
      //     }
      //   }
      //   // '/api': {
      //   //   target: process.env.VITE_BASE_URL,
      //   //   changeOrigin: true,
      //   // }
      // }
    },
    css: {
      /** 通过多线程运行 CSS 预处理器，从而极大提高其处理速度 */
      preprocessorOptions: {
        scss: {
          additionalData: `
            @use "./src/styles/common.scss" as *;
            @use "./src/styles/variables.scss" as *;
          `
        }
      }
    },
    build: {
      /** 指定打包文件的输出目录。默认值为 dist ，当 dist 被占用或公司有统一命名规范时，可进行调整 */
      outDir: VITE_ENV.VITE_OUTPUT_DIR,
      /** 图片转 base64 编码的阈值。为防止过多的 http 请求，Vite 会将小于此阈值的图片转为 base64 格式 */
      assetsInlineLimit: 4096,
      /** 规定触发警告的 chunk 大小。（以 kbs 为单位） */
      chunkSizeWarningLimit: 500,
      /** 启用/禁用 CSS 代码拆分 */
      cssCodeSplit: true,
      /** 构建后是否生成 source map 文件 */
      sourcemap: false,
      /** 是否在构建阶段将 publicDir 目录中的所有文件复制到 outDir 目录中 */
      copyPublicDir: true,
      /** 指定使用哪种混淆器。默认为 esbuild，它比 terser 快 20-40 倍，压缩率只差 1%-2% */
      minify: "esbuild",
      rollupOptions: {
        output: {
          // 引入文件名的名称
          chunkFileNames: "js/[name]-[hash].js",
          // 包的入口文件名称
          entryFileNames: "js/[name]-[hash].js",
          // 对打包出来的资源文件进行分类，分别放到不同的文件夹内
          assetFileNames(chunkInfo) {
            if (chunkInfo.name.endsWith(".css")) {
              return "css/[name]-[hash].css";
            }
            const fontExtList = [".ttf", ".otf", ".woff", ".woff2", ".eot"];
            if (fontExtList.some((ext) => chunkInfo.name.endsWith(ext))) {
              return "font/[name]-[hash].[ext]";
            }
            const imgExtList = ["png", "jpg", ".jpeg", "gif", "webp"];
            if (imgExtList.some((ext) => chunkInfo.name.endsWith(ext))) {
              return `img/[name]-[hash].[ext]`;
            }
            const videoExtList = [".mp4", ".avi", ".wmv", ".ram", ".mpg", "mpeg", ".m3u8"];
            if (videoExtList.some((ext) => chunkInfo.name.endsWith(ext))) {
              return `video/[name]-[hash].[ext]`;
            }
            return "assets/[name]-[hash].[ext]";
          },
          // 打包的文件进行拆包处理
          manualChunks(chunk) {
            if (chunk.includes("node_modules")) return "vendor";
          }
        }
      }
    },
    /** 打包后移除所有的 console、debugger */
    esbuild: {
      drop: VITE_ENV.VITE_DROP_CONSOLE ? ["console", "debugger"] : []
    }
  };
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcudHMiLCAiYnVpbGQvaW5kZXgudHMiLCAiYnVpbGQvc2NyaXB0cy9nZW5lcmF0ZS1zdmctdHlwZS50cyIsICJidWlsZC9wbHVnaW5zL2luZGV4LnRzIiwgImJ1aWxkL3BsdWdpbnMvYXV0by1pbXBvcnQtcGx1Z2luLnRzIiwgImJ1aWxkL3BsdWdpbnMvY29tcGlsZS5odG1sLnRzIiwgImJ1aWxkL3BsdWdpbnMvaW1hZ2UubWluaS50cyIsICJidWlsZC9wbHVnaW5zL3JlZ2lzdGVyLXN2Zy1pY29ucy50cyJdLAogICJzb3VyY2VzQ29udGVudCI6IFsiY29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2Rpcm5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXDE1ODk5XFxcXERvY3VtZW50c1xcXFx2c2NvZGVTcGFjZVxcXFxzdW5cXFxcc3VuQ3VycmVuY3lcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXDE1ODk5XFxcXERvY3VtZW50c1xcXFx2c2NvZGVTcGFjZVxcXFxzdW5cXFxcc3VuQ3VycmVuY3lcXFxcdml0ZS5jb25maWcudHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L1VzZXJzLzE1ODk5L0RvY3VtZW50cy92c2NvZGVTcGFjZS9zdW4vc3VuQ3VycmVuY3kvdml0ZS5jb25maWcudHNcIjtpbXBvcnQgeyBkZWZpbmVDb25maWcsIGxvYWRFbnYgfSBmcm9tICd2aXRlJyAvLyBcdTRGN0ZcdTc1MjggZGVmaW5lQ29uZmlnIFx1NURFNVx1NTE3N1x1NTFGRFx1NjU3MFx1RkYwQ1x1OEZEOVx1NjgzN1x1NEUwRFx1NzUyOCBqc2RvYyBcdTZDRThcdTg5RTNcdTRFNUZcdTUzRUZcdTRFRTVcdTgzQjdcdTUzRDZcdTdDN0JcdTU3OEJcdTYzRDBcdTc5M0FcclxuaW1wb3J0IHsgd2FycHBlckVudiwgcGF0aFJlc29sdmUgfSBmcm9tICcuL2J1aWxkJyAvLyBcdTVGMTVcdTUxNjVcdTVCRjlcdTczQUZcdTU4ODNcdTUzRDhcdTkxQ0ZcdTc2ODRcdTU5MDRcdTc0MDZcdTUxRkRcdTY1NzBcclxuaW1wb3J0IHsgZ2VuZXJhdGVWaXRlUGx1Z2lucyB9IGZyb20gJy4vYnVpbGQvcGx1Z2lucycgLy8gXHU1RjE1XHU1MTY1XHU2MkJEXHU3OUJCXHU1MUZBXHU1M0JCXHU3Njg0IHZpdGUgXHU2M0QyXHU0RUY2XHU5NkM2XHU1NDA4XHJcbmltcG9ydCB7IGNyZWF0ZVN2Z0ljb25zUGx1Z2luIH0gZnJvbSAndml0ZS1wbHVnaW4tc3ZnLWljb25zJ1xyXG5pbXBvcnQgcGF0aCBmcm9tICdwYXRoJztcclxuLyoqIFx1NUY1M1x1NTI0RFx1NjI2N1x1ODg0QyBub2RlIFx1NTQ3RFx1NEVFNFx1NjVGNlx1NjU4N1x1NEVGNlx1NTkzOVx1NzY4NFx1NTczMFx1NTc0MFx1RkYwOFx1NURFNVx1NEY1Q1x1NzZFRVx1NUY1NVx1RkYwOSBcdTUzNzNcdTk4NzlcdTc2RUVcdTY4MzlcdTc2RUVcdTVGNTVcdUZGMDhcdTRFNUZcdTVDMzFcdTY2MkYgaW5kZXguaHRtbCBcdTY1ODdcdTRFRjZcdTYyNDBcdTU3MjhcdTc2ODRcdTRGNERcdTdGNkVcdUZGMDkgKi9cclxuY29uc3Qgcm9vdDogc3RyaW5nID0gcHJvY2Vzcy5jd2QoKVxyXG5cclxuLy8gaHR0cHM6Ly92aXRlanMuZGV2L2NvbmZpZy9cclxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKCh7IGNvbW1hbmQsIG1vZGUgfSkgPT4ge1xyXG4gIC8vIFx1NjgzOVx1NjM2RVx1NUY1M1x1NTI0RFx1NURFNVx1NEY1Q1x1NzZFRVx1NUY1NVx1NEUyRFx1NzY4NCBgbW9kZWAgXHU1MkEwXHU4RjdEIC5lbnYgXHU2NTg3XHU0RUY2XHJcbiAgLy8gXHU4QkJFXHU3RjZFXHU3QjJDXHU0RTA5XHU0RTJBXHU1M0MyXHU2NTcwXHU0RTNBICcnIFx1Njc2NVx1NTJBMFx1OEY3RFx1NjI0MFx1NjcwOVx1NzNBRlx1NTg4M1x1NTNEOFx1OTFDRlx1RkYwQ1x1ODAwQ1x1NEUwRFx1N0JBMVx1NjYyRlx1NTQyNlx1NjcwOSBgVklURV9gIFx1NTI0RFx1N0YwMFxyXG4gIGNvbnN0IFZJVEVfRU5WID0gd2FycHBlckVudihsb2FkRW52KG1vZGUsIHJvb3QsICdWSVRFXycpKSAvLyBcdTVCRjlcdTUzOUZcdTc1MUZcdTczQUZcdTU4ODNcdTUzRDhcdTkxQ0ZcdThGREJcdTg4NENcdTRFOENcdTZCMjFcdTU5MDRcdTc0MDZcclxuICBjb25zdCBpc0J1aWxkID0gY29tbWFuZCA9PT0gJ2J1aWxkJyAvLyBcdTVGNTNcdTUyNERcdTY2MkZcdTU0MjZcdTY2MkZcdTc1MUZcdTRFQTdcdTZBMjFcdTVGMEZcclxuXHJcbiAgcmV0dXJuIHtcclxuICAgIC8vIFx1OTBFOFx1N0Y3Mlx1NUU5NFx1NzUyOFx1NTMwNVx1NjVGNlx1NzY4NFx1NTdGQVx1NjcyQyBVUkxcclxuICAgIGJhc2U6IFZJVEVfRU5WLlZJVEVfUFVCTElDX1BBVEgsXHJcblxyXG4gICAgcGx1Z2luczogW1xyXG4gICAgICBnZW5lcmF0ZVZpdGVQbHVnaW5zKFZJVEVfRU5WLCBpc0J1aWxkKSxcclxuICAgICAgLy8gY3JlYXRlU3ZnSWNvbnNQbHVnaW4oe1xyXG4gICAgICAvLyAgIC8vIFx1NjMwN1x1NUI5QVx1OTcwMFx1ODk4MVx1N0YxM1x1NUI1OFx1NzY4NFx1NTZGRVx1NjgwN1x1NjU4N1x1NEVGNlx1NTkzOVxyXG4gICAgICAvLyAgIGljb25EaXJzOiBbcGF0aC5yZXNvbHZlKHByb2Nlc3MuY3dkKCksICdzcmMvYXNzZXRzL2ljb25zJyldLFxyXG4gICAgICAvLyAgIC8vIFx1NjMwN1x1NUI5QXN5bWJvbElkXHU2ODNDXHU1RjBGXHJcbiAgICAgIC8vICAgc3ltYm9sSWQ6ICdpY29uLVtkaXJdLVtuYW1lXSdcclxuICAgICAgLy8gfSlcclxuICAgIF0sXHJcbiAgICAvLyBcdTVCODlcdTg4QzVcdUZGMUFzdHlsZS1yZXNvdXJjZXMtbG9hZGVyXHJcbiAgICAvLyBwbHVnaW5PcHRpb25zOiB7XHJcbiAgICAvLyAgICdzdHlsZS1yZXNvdXJjZXMtbG9hZGVyJzoge1xyXG4gICAgLy8gICAgIHByZVByb2Nlc3NvcjogJ3Njc3MnLFxyXG4gICAgLy8gICAgIC8vIFx1OEZEOVx1NEUwOVx1NzlDRCBwYXR0ZXJucyBcdTUxOTlcdTZDRDVcdTkwRkRcdTY2MkZcdTUzRUZcdTRFRTVcdTc2ODRcclxuICAgIC8vICAgICAvLyBwYXR0ZXJuczogW1wiLi9zcmMvYXNzZXRzL3Jlc2V0MS5sZXNzXCIsIFwiLi9zcmMvYXNzZXRzL3Jlc2V0Mi5sZXNzXCJdXHJcbiAgICAvLyAgICAgLy8gcGF0dGVybnM6IFwiLi9zcmMvYXNzZXRzL3Jlc2V0Lmxlc3NcIlxyXG4gICAgLy8gICAgIHBhdHRlcm5zOiBbXHJcbiAgICAvLyAgICAgICAvLyBcdTRFMjRcdTc5Q0RcdThERUZcdTVGODRcdTUxOTlcdTZDRDVcdTkwRkRcdTUzRUZcdTRFRTVcdUZGMENcdThGRDlcdTkxQ0NcdTc2ODRcdThERUZcdTVGODRcdTRFMERcdTgwRkRcdTRGN0ZcdTc1MjggQCBcdTdCMjZcdTUzRjdcdUZGMENcdTU0MjZcdTUyMTlcdTRGMUFcdTYyQTVcdTk1MTlcclxuICAgIC8vICAgICAgIC8vIHBhdGgucmVzb2x2ZShfX2Rpcm5hbWUsICcuL3NyYy9hc3NldHMvcmVzZXQubGVzcycpXHJcbiAgICAvLyAgICAgICBwYXRoLnJlc29sdmUoX19kaXJuYW1lLCAnc3JjL3N0eWxlcy92YXJpYWJsZXMuc2NzcycpXHJcbiAgICAvLyAgICAgXVxyXG4gICAgLy8gICB9XHJcbiAgICAvLyB9LFxyXG4gICAgcmVzb2x2ZToge1xyXG4gICAgICBhbGlhczogW1xyXG4gICAgICAgIC8qKiBcdThCQkVcdTdGNkUgYEBgIFx1NjMwN1x1NTQxMSBgc3JjYCBcdTc2RUVcdTVGNTUgKi9cclxuICAgICAgICB7IGZpbmQ6ICdAJywgcmVwbGFjZW1lbnQ6IHBhdGhSZXNvbHZlKCdzcmMnKSB9LFxyXG4gICAgICAgIC8qKiBcdThCQkVcdTdGNkUgYCNgIFx1NjMwN1x1NTQxMSBgdHlwZXNgIFx1NzZFRVx1NUY1NSAqL1xyXG4gICAgICAgIHsgZmluZDogJyMnLCByZXBsYWNlbWVudDogcGF0aFJlc29sdmUoJ3R5cGVzJykgfSxcclxuICAgICAgXSxcclxuICAgIH0sXHJcblxyXG4gICAgc2VydmVyOiB7XHJcbiAgICAgIC8qKiBcdThCQkVcdTdGNkVcdTRFM0EgMC4wLjAuMCBcdTYyMTZcdTgwMDUgdHJ1ZSBcdTVDMDZcdTc2RDFcdTU0MkNcdTYyNDBcdTY3MDlcdTU3MzBcdTU3NDBcdUZGMENcdTUzMDVcdTYyRUNcdTVDNDBcdTU3REZcdTdGNTFcdTU0OENcdTUxNkNcdTdGNTFcdTU3MzBcdTU3NDAgKi9cclxuICAgICAgaG9zdDogdHJ1ZSxcclxuICAgICAgY29yczogdHJ1ZSxcclxuICAgICAgLyoqIFx1NTNDRFx1NTQxMVx1NEVFM1x1NzQwNlx1OTE0RFx1N0Y2RVx1RkYwOFx1NEUzQlx1ODk4MVx1NjYyRlx1NUYwMFx1NTNEMVx1NjVGNlx1NzUyOFx1Njc2NVx1ODlFM1x1NTFCM1x1OERFOFx1NTdERlx1OTVFRVx1OTg5OFx1RkYwOSAqL1xyXG4gICAgICAvLyBwcm94eToge1xyXG4gICAgICAvLyAgIC8vIFtwcm9jZXNzLmVudi5WSVRFX0JBU0VfQVBJXTogcHJvY2Vzcy5lbnYuVklURV9CQVNFX1VSTCxcclxuICAgICAgLy8gICAnL2FwaSc6IHtcclxuICAgICAgLy8gICAgIHRhcmdldDogcHJvY2Vzcy5lbnYuVklURV9CQVNFX1VSTCxcclxuICAgICAgLy8gICAgIGNoYW5nZU9yaWdpbjogdHJ1ZSxcclxuICAgICAgLy8gICAgIHJld3JpdGU6IChwYXRoKSA9PiBwYXRoLnJlcGxhY2UoL1xcL2FwaS8sICcvYXBpJyksXHJcbiAgICAgIC8vICAgICBieXBhc3MocmVxLCByZXMsIG9wdGlvbnMpIHtcclxuXHQgICAgLy8gICAgICAgLy8gY29uc3QgcHJveHlVUkwgPSBvcHRpb25zLnRhcmdldCArIG9wdGlvbnMucmV3cml0ZShyZXEudXJsKTtcclxuXHQgICAgLy8gICAgICAgLy8gcmVzLnNldEhlYWRlcigneC1yZXEtcHJveHlVUkwnLCBwcm94eVVSTCkgLy8gXHU1QzA2XHU3NzFGXHU1QjlFXHU4QkY3XHU2QzQyXHU1NzMwXHU1NzQwXHU4QkJFXHU3RjZFXHU1MjMwXHU1NENEXHU1RTk0XHU1OTM0XHU0RTJEXHJcblx0ICAgIC8vICAgICB9XHJcbiAgICAgIC8vICAgfVxyXG4gICAgICAvLyAgIC8vICcvYXBpJzoge1xyXG4gICAgICAvLyAgIC8vICAgdGFyZ2V0OiBwcm9jZXNzLmVudi5WSVRFX0JBU0VfVVJMLFxyXG4gICAgICAvLyAgIC8vICAgY2hhbmdlT3JpZ2luOiB0cnVlLFxyXG4gICAgICAvLyAgIC8vIH1cclxuICAgICAgLy8gfVxyXG4gICAgfSxcclxuXHJcbiAgICBjc3M6IHtcclxuICAgICAgLyoqIFx1OTAxQVx1OEZDN1x1NTkxQVx1N0VCRlx1N0EwQlx1OEZEMFx1ODg0QyBDU1MgXHU5ODg0XHU1OTA0XHU3NDA2XHU1NjY4XHVGRjBDXHU0RUNFXHU4MDBDXHU2NzgxXHU1OTI3XHU2M0QwXHU5QUQ4XHU1MTc2XHU1OTA0XHU3NDA2XHU5MDFGXHU1RUE2ICovXHJcbiAgICAgIHByZXByb2Nlc3Nvck9wdGlvbnM6IHtcclxuICAgICAgICBzY3NzOiB7XHJcbiAgICAgICAgICBhZGRpdGlvbmFsRGF0YTogYFxyXG4gICAgICAgICAgICBAdXNlIFwiLi9zcmMvc3R5bGVzL2NvbW1vbi5zY3NzXCIgYXMgKjtcclxuICAgICAgICAgICAgQHVzZSBcIi4vc3JjL3N0eWxlcy92YXJpYWJsZXMuc2Nzc1wiIGFzICo7XHJcbiAgICAgICAgICBgXHJcbiAgICAgICAgfVxyXG4gICAgICB9XHJcbiAgICB9LFxyXG5cclxuICAgIGJ1aWxkOiB7XHJcbiAgICAgIC8qKiBcdTYzMDdcdTVCOUFcdTYyNTNcdTUzMDVcdTY1ODdcdTRFRjZcdTc2ODRcdThGOTNcdTUxRkFcdTc2RUVcdTVGNTVcdTMwMDJcdTlFRDhcdThCQTRcdTUwM0NcdTRFM0EgZGlzdCBcdUZGMENcdTVGNTMgZGlzdCBcdTg4QUJcdTUzNjBcdTc1MjhcdTYyMTZcdTUxNkNcdTUzRjhcdTY3MDlcdTdFREZcdTRFMDBcdTU0N0RcdTU0MERcdTg5QzRcdTgzMDNcdTY1RjZcdUZGMENcdTUzRUZcdThGREJcdTg4NENcdThDMDNcdTY1NzQgKi9cclxuICAgICAgb3V0RGlyOiBWSVRFX0VOVi5WSVRFX09VVFBVVF9ESVIsXHJcbiAgICAgIC8qKiBcdTU2RkVcdTcyNDdcdThGNkMgYmFzZTY0IFx1N0YxNlx1NzgwMVx1NzY4NFx1OTYwOFx1NTAzQ1x1MzAwMlx1NEUzQVx1OTYzMlx1NkI2Mlx1OEZDN1x1NTkxQVx1NzY4NCBodHRwIFx1OEJGN1x1NkM0Mlx1RkYwQ1ZpdGUgXHU0RjFBXHU1QzA2XHU1QzBGXHU0RThFXHU2QjY0XHU5NjA4XHU1MDNDXHU3Njg0XHU1NkZFXHU3MjQ3XHU4RjZDXHU0RTNBIGJhc2U2NCBcdTY4M0NcdTVGMEYgKi9cclxuICAgICAgYXNzZXRzSW5saW5lTGltaXQ6IDQwOTYsXHJcbiAgICAgIC8qKiBcdTg5QzRcdTVCOUFcdTg5RTZcdTUzRDFcdThCNjZcdTU0NEFcdTc2ODQgY2h1bmsgXHU1OTI3XHU1QzBGXHUzMDAyXHVGRjA4XHU0RUU1IGticyBcdTRFM0FcdTUzNTVcdTRGNERcdUZGMDkgKi9cclxuICAgICAgY2h1bmtTaXplV2FybmluZ0xpbWl0OiA1MDAsXHJcbiAgICAgIC8qKiBcdTU0MkZcdTc1MjgvXHU3OTgxXHU3NTI4IENTUyBcdTRFRTNcdTc4MDFcdTYyQzZcdTUyMDYgKi9cclxuICAgICAgY3NzQ29kZVNwbGl0OiB0cnVlLFxyXG4gICAgICAvKiogXHU2Nzg0XHU1RUZBXHU1NDBFXHU2NjJGXHU1NDI2XHU3NTFGXHU2MjEwIHNvdXJjZSBtYXAgXHU2NTg3XHU0RUY2ICovXHJcbiAgICAgIHNvdXJjZW1hcDogZmFsc2UsXHJcbiAgICAgIC8qKiBcdTY2MkZcdTU0MjZcdTU3MjhcdTY3ODRcdTVFRkFcdTk2MzZcdTZCQjVcdTVDMDYgcHVibGljRGlyIFx1NzZFRVx1NUY1NVx1NEUyRFx1NzY4NFx1NjI0MFx1NjcwOVx1NjU4N1x1NEVGNlx1NTkwRFx1NTIzNlx1NTIzMCBvdXREaXIgXHU3NkVFXHU1RjU1XHU0RTJEICovXHJcbiAgICAgIGNvcHlQdWJsaWNEaXI6IHRydWUsXHJcbiAgICAgIC8qKiBcdTYzMDdcdTVCOUFcdTRGN0ZcdTc1MjhcdTU0RUFcdTc5Q0RcdTZERjdcdTZEQzZcdTU2NjhcdTMwMDJcdTlFRDhcdThCQTRcdTRFM0EgZXNidWlsZFx1RkYwQ1x1NUI4M1x1NkJENCB0ZXJzZXIgXHU1RkVCIDIwLTQwIFx1NTAwRFx1RkYwQ1x1NTM4Qlx1N0YyOVx1NzM4N1x1NTNFQVx1NURFRSAxJS0yJSAqL1xyXG4gICAgICBtaW5pZnk6ICdlc2J1aWxkJyxcclxuICAgICAgcm9sbHVwT3B0aW9uczoge1xyXG4gICAgICAgIG91dHB1dDoge1xyXG4gICAgICAgICAgLy8gXHU1RjE1XHU1MTY1XHU2NTg3XHU0RUY2XHU1NDBEXHU3Njg0XHU1NDBEXHU3OUYwXHJcbiAgICAgICAgICBjaHVua0ZpbGVOYW1lczogJ2pzL1tuYW1lXS1baGFzaF0uanMnLFxyXG4gICAgICAgICAgLy8gXHU1MzA1XHU3Njg0XHU1MTY1XHU1M0UzXHU2NTg3XHU0RUY2XHU1NDBEXHU3OUYwXHJcbiAgICAgICAgICBlbnRyeUZpbGVOYW1lczogJ2pzL1tuYW1lXS1baGFzaF0uanMnLFxyXG4gICAgICAgICAgLy8gXHU1QkY5XHU2MjUzXHU1MzA1XHU1MUZBXHU2NzY1XHU3Njg0XHU4RDQ0XHU2RTkwXHU2NTg3XHU0RUY2XHU4RkRCXHU4ODRDXHU1MjA2XHU3QzdCXHVGRjBDXHU1MjA2XHU1MjJCXHU2NTNFXHU1MjMwXHU0RTBEXHU1NDBDXHU3Njg0XHU2NTg3XHU0RUY2XHU1OTM5XHU1MTg1XHJcbiAgICAgICAgICBhc3NldEZpbGVOYW1lcyhjaHVua0luZm8pIHtcclxuICAgICAgICAgICAgLy8gY3NzIFx1NjgzN1x1NUYwRlx1NjU4N1x1NEVGNlxyXG4gICAgICAgICAgICBpZiAoY2h1bmtJbmZvLm5hbWUuZW5kc1dpdGgoJy5jc3MnKSkge1xyXG4gICAgICAgICAgICAgIHJldHVybiAnY3NzL1tuYW1lXS1baGFzaF0uY3NzJ1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIFx1NUI1N1x1NEY1M1x1NjU4N1x1NEVGNlxyXG4gICAgICAgICAgICBjb25zdCBmb250RXh0TGlzdCA9IFsnLnR0ZicsICcub3RmJywgJy53b2ZmJywgJy53b2ZmMicsICcuZW90J11cclxuICAgICAgICAgICAgaWYgKGZvbnRFeHRMaXN0LnNvbWUoKGV4dCkgPT4gY2h1bmtJbmZvLm5hbWUuZW5kc1dpdGgoZXh0KSkpIHtcclxuICAgICAgICAgICAgICByZXR1cm4gJ2ZvbnQvW25hbWVdLVtoYXNoXS5bZXh0XSdcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICAvLyBcdTU2RkVcdTcyNDdcdTY1ODdcdTRFRjZcclxuICAgICAgICAgICAgY29uc3QgaW1nRXh0TGlzdCA9IFsncG5nJywgJ2pwZycsICcuanBlZycsICdnaWYnLCAnd2VicCddXHJcbiAgICAgICAgICAgIGlmIChpbWdFeHRMaXN0LnNvbWUoKGV4dCkgPT4gY2h1bmtJbmZvLm5hbWUuZW5kc1dpdGgoZXh0KSkpIHtcclxuICAgICAgICAgICAgICByZXR1cm4gYGltZy9bbmFtZV0tW2hhc2hdLltleHRdYFxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIFx1ODlDNlx1OTg5MVx1NjU4N1x1NEVGNlxyXG4gICAgICAgICAgICBjb25zdCB2aWRlb0V4dExpc3QgPSBbJy5tcDQnLCAnLmF2aScsICcud212JywgJy5yYW0nLCAnLm1wZycsICdtcGVnJywgJy5tM3U4J11cclxuICAgICAgICAgICAgaWYgKHZpZGVvRXh0TGlzdC5zb21lKChleHQpID0+IGNodW5rSW5mby5uYW1lLmVuZHNXaXRoKGV4dCkpKSB7XHJcbiAgICAgICAgICAgICAgcmV0dXJuIGB2aWRlby9bbmFtZV0tW2hhc2hdLltleHRdYFxyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIC8vIFx1NTE3Nlx1NUI4M1x1NjU4N1x1NEVGNjogXHU0RkREXHU1QjU4XHU1NzI4IGFzc2V0cy9cdTY1ODdcdTRFRjZcdTU0MEQtXHU1NEM4XHU1RTBDXHU1MDNDLlx1NjI2OVx1NUM1NVx1NTQwRFxyXG4gICAgICAgICAgICByZXR1cm4gJ2Fzc2V0cy9bbmFtZV0tW2hhc2hdLltleHRdJ1xyXG4gICAgICAgICAgfSxcclxuICAgICAgICAgIC8vIFx1NjI1M1x1NTMwNVx1NzY4NFx1NjU4N1x1NEVGNlx1OEZEQlx1ODg0Q1x1NjJDNlx1NTMwNVx1NTkwNFx1NzQwNlxyXG4gICAgICAgICAgbWFudWFsQ2h1bmtzKGNodW5rKSB7XHJcbiAgICAgICAgICAgIC8vIFx1OEZEOVx1NEUyQSBjaHVuayBcdTVDMzFcdTY2MkZcdTYyNDBcdTY3MDlcdTY1ODdcdTRFRjZcdTc2ODRcdTdFRERcdTVCRjlcdThERUZcdTVGODRcclxuICAgICAgICAgICAgLy8gXHU1NkUwXHU0RTNBIG5vZGVfbW9kdWxlcyBcdTRFMkRcdTc2ODRcdTRGOURcdThENTZcdTkwMUFcdTVFMzhcdTY2MkZcdTRFMERcdTRGMUFcdTY1MzlcdTUzRDhcdTc2ODQgXHU2MjQwXHU0RUU1XHU3NkY0XHU2M0E1XHU1MzU1XHU3MkVDXHU2MjUzXHU1MzA1XHU1MUZBXHU1M0JCXHJcbiAgICAgICAgICAgIC8vIFx1OEZEOVx1NEUyQSByZXR1cm4gXHU3Njg0XHU1MDNDXHU1QzMxXHU2NjJGXHU2MjUzXHU1MzA1XHU3Njg0XHU1NDBEXHU3OUYwXHJcbiAgICAgICAgICAgIC8vIFx1NTNFRlx1NEVFNVx1NTIyOVx1NzUyOFx1NkQ0Rlx1ODlDOFx1NTY2OFx1NzY4NFx1N0YxM1x1NUI1OFx1NjczQVx1NTIzNiBcdTUxQ0ZcdTVDMTFcdThCRjdcdTZDNDJcdTZCMjFcdTY1NzBcclxuICAgICAgICAgICAgaWYgKGNodW5rLmluY2x1ZGVzKCdub2RlX21vZHVsZXMnKSkgcmV0dXJuICd2ZW5kb3InXHJcbiAgICAgICAgICB9LFxyXG4gICAgICAgIH0sXHJcbiAgICAgIH0sXHJcbiAgICB9LFxyXG5cclxuICAgIC8qKiBcdTYyNTNcdTUzMDVcdTU0MEVcdTc5RkJcdTk2NjRcdTYyNDBcdTY3MDlcdTc2ODQgY29uc29sZVx1MzAwMWRlYnVnZ2VyICovXHJcbiAgICBlc2J1aWxkOiB7XHJcbiAgICAgIGRyb3A6IFZJVEVfRU5WLlZJVEVfRFJPUF9DT05TT0xFID8gWydjb25zb2xlJywgJ2RlYnVnZ2VyJ10gOiBbXSxcclxuICAgIH0sXHJcbiAgfVxyXG59KVxyXG4iLCAiY29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2Rpcm5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXDE1ODk5XFxcXERvY3VtZW50c1xcXFx2c2NvZGVTcGFjZVxcXFxzdW5cXFxcc3VuQ3VycmVuY3lcXFxcYnVpbGRcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXDE1ODk5XFxcXERvY3VtZW50c1xcXFx2c2NvZGVTcGFjZVxcXFxzdW5cXFxcc3VuQ3VycmVuY3lcXFxcYnVpbGRcXFxcaW5kZXgudHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L1VzZXJzLzE1ODk5L0RvY3VtZW50cy92c2NvZGVTcGFjZS9zdW4vc3VuQ3VycmVuY3kvYnVpbGQvaW5kZXgudHNcIjtpbXBvcnQgcGF0aCBmcm9tICdwYXRoJyAvLyBwYXRoIFx1NkEyMVx1NTc1N1x1NjNEMFx1NEY5Qlx1NEU4Nlx1NEUwMFx1NEU5Qlx1NURFNVx1NTE3N1x1NTFGRFx1NjU3MFx1RkYwQ1x1NzUyOFx1NEU4RVx1NTkwNFx1NzQwNlx1NjU4N1x1NEVGNlx1NEUwRVx1NzZFRVx1NUY1NVx1NzY4NFx1OERFRlx1NUY4NFxyXG5pbXBvcnQgJy4vc2NyaXB0cy9nZW5lcmF0ZS1zdmctdHlwZScgLy8gXHU5ODc5XHU3NkVFXHU1NDJGXHU1MkE4XHU2NUY2XHU4MUVBXHU1MkE4XHU3NTFGXHU2MjEwIFN2Z0ljb24gXHU3Njg0XHU3QzdCXHU1NzhCXHJcblxyXG4vKiogXHU1OTA0XHU3NDA2XHU3M0FGXHU1ODgzXHU1M0Q4XHU5MUNGICovXHJcbmV4cG9ydCBmdW5jdGlvbiB3YXJwcGVyRW52KGVudl9jb25maWc6IFJlY29yZGFibGU8c3RyaW5nPik6IFZpdGVFbnYge1xyXG4gIGNvbnN0IGVudiA9IHt9IGFzIFZpdGVFbnZcclxuXHJcbiAgZm9yIChjb25zdCBba2V5LCB2YWx1ZV0gb2YgT2JqZWN0LmVudHJpZXMoZW52X2NvbmZpZykpIHtcclxuICAgIGlmIChbJ3RydWUnLCAnZmFsc2UnXS5pbmNsdWRlcyh2YWx1ZSkpIHtcclxuICAgICAgZW52W2tleV0gPSB2YWx1ZSA9PT0gJ3RydWUnID8gdHJ1ZSA6IGZhbHNlIC8vIFx1NUUwM1x1NUMxNFx1N0M3Qlx1NTc4Qlx1NTkwNFx1NzQwNlxyXG4gICAgfSBlbHNlIGlmIChOdW1iZXIodmFsdWUpKSB7XHJcbiAgICAgIGVudltrZXldID0gTnVtYmVyKHZhbHVlKSAvLyBcdTY1NzBcdTUwM0NcdTdDN0JcdTU3OEJcdTU5MDRcdTc0MDZcclxuICAgIH0gZWxzZSB7XHJcbiAgICAgIGVudltrZXldID0gdmFsdWUgLy8gXHU1QjU3XHU3QjI2XHU0RTMyXHU3QzdCXHU1NzhCXHU1MzlGXHU1MDNDXHU1MzczXHU1M0VGXHJcbiAgICB9XHJcbiAgfVxyXG5cclxuICByZXR1cm4gZW52XHJcbn1cclxuXHJcbi8qKiBcdThERUZcdTVGODRcdTYyRkNcdTYzQTVcdTUxRkRcdTY1NzBcdUZGMENcdTdCODBcdTUzMTZcdTRFRTNcdTc4MDEgXHU3NTI4IG5vcm1hbGl6ZVBhdGggXHU4OUUzXHU1MUIzIHdpbmRvdyBcdTRFMEJcdTc2ODRcdThERUZcdTVGODRcdTk1RUVcdTk4OTggKi9cclxuZXhwb3J0IGZ1bmN0aW9uIHBhdGhSZXNvbHZlKGRpcjogc3RyaW5nKTogc3RyaW5nIHtcclxuICByZXR1cm4gcGF0aC5ub3JtYWxpemUocGF0aC5yZXNvbHZlKHByb2Nlc3MuY3dkKCksIGRpcikpXHJcbn1cclxuIiwgImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFwxNTg5OVxcXFxEb2N1bWVudHNcXFxcdnNjb2RlU3BhY2VcXFxcc3VuXFxcXHN1bkN1cnJlbmN5XFxcXGJ1aWxkXFxcXHNjcmlwdHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXDE1ODk5XFxcXERvY3VtZW50c1xcXFx2c2NvZGVTcGFjZVxcXFxzdW5cXFxcc3VuQ3VycmVuY3lcXFxcYnVpbGRcXFxcc2NyaXB0c1xcXFxnZW5lcmF0ZS1zdmctdHlwZS50c1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQzovVXNlcnMvMTU4OTkvRG9jdW1lbnRzL3ZzY29kZVNwYWNlL3N1bi9zdW5DdXJyZW5jeS9idWlsZC9zY3JpcHRzL2dlbmVyYXRlLXN2Zy10eXBlLnRzXCI7aW1wb3J0IGZzIGZyb20gJ2ZzL3Byb21pc2VzJ1xyXG5pbXBvcnQgeyBwYXRoUmVzb2x2ZSB9IGZyb20gJy4uJ1xyXG5cclxuLyoqIFN2ZyBJY29uIFx1NzY4NFx1NUI1OFx1NjUzRVx1NzZFRVx1NUY1NSAqL1xyXG5jb25zdCBzdmdJY29uRGlyID0gcGF0aFJlc29sdmUoJ3NyYy9hc3NldHMvc3ZnLWljb25zJylcclxuLyoqIFN2ZyBJY29uIFx1NThGMFx1NjYwRVx1NjU4N1x1NEVGNlx1NzY4NFx1NUI1OFx1NjUzRVx1NEY0RFx1N0Y2RSAqL1xyXG5jb25zdCBzdmdJY29uVHlwZUZpbGVVUkwgPSBwYXRoUmVzb2x2ZSgndHlwZXMvYXV0by1nZW5lcmF0ZS9hdXRvLXN2Zy1uYW1lLmQudHMnKVxyXG5jb25zdCBpY29uRGF0YUZpbGUgPSBwYXRoUmVzb2x2ZSgnc3JjL2NvbXBvbmVudHMvU3ZnSWNvbi9pY29ucy50cycpXHJcblxyXG4vKiogXHU3NTI4XHU2NzY1XHU4MUVBXHU1MkE4XHU4QkZCXHU1M0Q2IHN2ZyBcdTU2RkVcdTY4MDdcdTc2RUVcdTVGNTVcdTVFNzZcdTc1MjhcdTUxNzZcdTU0MERcdTVCNTdcdTc1MUZcdTYyMTBcdTRFMDBcdTRFMkEgU3ZnIFx1NTQwRFx1NUI1N1x1NzY4NFx1Njc5QVx1NEUzRVx1NTAzQ1x1RkYxQlx1NkJDRlx1NkIyMVx1NjVCMFx1NTg5RVx1NTZGRVx1NjgwN1x1OTBGRFx1OTcwMFx1ODk4MVx1OTFDRFx1NTQyRlx1OTg3OVx1NzZFRVx1Njc2NVx1NjZGNFx1NjVCMFx1NThGMFx1NjYwRVx1NjU4N1x1NEVGNiAqL1xyXG5hc3luYyBmdW5jdGlvbiBnZW5lcmF0ZVN2Z0ljb25UeXBlKGRpcjogc3RyaW5nLCBvdXRwdXRGaWxlOiBzdHJpbmcpIHtcclxuICB0cnkge1xyXG4gICAgLy8gXHU4QkZCXHU1M0Q2XHU2MzA3XHU1QjlBXHU3NkVFXHU1RjU1XHU0RTBCXHU3Njg0XHU2MjQwXHU2NzA5XHU1NkZFXHU2ODA3XHU2NTg3XHU0RUY2XHU1NDBEIFx1NTQyQlx1NTQwRVx1N0YwMFxyXG4gICAgY29uc3QgaWNvbkZ1bGxOYW1lTGlzdCA9IGF3YWl0IGZzLnJlYWRkaXIoZGlyKVxyXG4gICAgLy8gXHU4M0I3XHU1M0Q2XHU1M0JCXHU5NjY0XHU1NDBFXHU3RjAwXHU1NDBFXHU3Njg0XHU2NTg3XHU0RUY2XHU1NDBEXHU2NTcwXHU3RUM0XHJcbiAgICBjb25zdCBpY29uTmFtZUxpc3QgPSBpY29uRnVsbE5hbWVMaXN0Lm1hcCgoaXRlbSkgPT4gaXRlbS5yZXBsYWNlKCcuc3ZnJywgJycpKVxyXG4gICAgLy8gXHU3NTFGXHU2MjEwIFN2Z0ljb24gXHU3Njg0IG5hbWUgXHU3Njg0XHU2NzlBXHU0RTNFXHU1MDNDXHU1QjU3XHU3QjI2XHU0RTMyXHJcbiAgICBjb25zdCB0eXBlcyA9IGljb25OYW1lTGlzdC5yZWR1Y2UoKHByZXYsIGl0ZW0sIGluZGV4KSA9PiAocHJldiArPSBpY29uTmFtZUxpc3QubGVuZ3RoIC0gMSA9PT0gaW5kZXggPyBgJyR7aXRlbX0nYCA6IGAnJHtpdGVtfScgfCBgKSwgJycpXHJcbiAgICAvLyBcdTUxOTlcdTUxNjVcdTU4RjBcdTY2MEVcdTY1ODdcdTRFRjYgXHU1OTgyXHU2NzlDXHU2NTg3XHU0RUY2XHU1REYyXHU1QjU4XHU1NzI4XHU1MjE5XHU4OTg2XHU3NkQ2XHJcbiAgICBhd2FpdCBmcy53cml0ZUZpbGUob3V0cHV0RmlsZSwgYHR5cGUgU3ZnSWNvbk5hbWUgPSAke3R5cGVzfWAsIHsgZmxhZzogJ3cnIH0pXHJcbiAgICBhd2FpdCBmcy53cml0ZUZpbGUoaWNvbkRhdGFGaWxlLCBgZXhwb3J0IGRlZmF1bHQgJHtKU09OLnN0cmluZ2lmeShpY29uTmFtZUxpc3QpfSBhcyBTdmdJY29uTmFtZVtdYClcclxuICB9IGNhdGNoIChlcnJvcikge1xyXG4gICAgY29uc29sZS5sb2coJ2Vycm9yOiAnLCBlcnJvcilcclxuICB9XHJcbn1cclxuXHJcbmdlbmVyYXRlU3ZnSWNvblR5cGUoc3ZnSWNvbkRpciwgc3ZnSWNvblR5cGVGaWxlVVJMKVxyXG4iLCAiY29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2Rpcm5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXDE1ODk5XFxcXERvY3VtZW50c1xcXFx2c2NvZGVTcGFjZVxcXFxzdW5cXFxcc3VuQ3VycmVuY3lcXFxcYnVpbGRcXFxccGx1Z2luc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxcVXNlcnNcXFxcMTU4OTlcXFxcRG9jdW1lbnRzXFxcXHZzY29kZVNwYWNlXFxcXHN1blxcXFxzdW5DdXJyZW5jeVxcXFxidWlsZFxcXFxwbHVnaW5zXFxcXGluZGV4LnRzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9DOi9Vc2Vycy8xNTg5OS9Eb2N1bWVudHMvdnNjb2RlU3BhY2Uvc3VuL3N1bkN1cnJlbmN5L2J1aWxkL3BsdWdpbnMvaW5kZXgudHNcIjtpbXBvcnQgdHlwZSB7IFBsdWdpbk9wdGlvbiB9IGZyb20gJ3ZpdGUnXHJcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJyAvLyBcdTYzRDBcdTRGOUIgVnVlIDMgXHU1MzU1XHU2NTg3XHU0RUY2XHU3RUM0XHU0RUY2XHU2NTJGXHU2MzAxXHJcbmltcG9ydCB2dWVKc3ggZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlLWpzeCcgLy8gXHU2M0QwXHU0RjlCIFZ1ZSAzIEpTWCBcdTY1MkZcdTYzMDFcclxuaW1wb3J0IFVub0NTUyBmcm9tICd1bm9jc3Mvdml0ZScgLy8gXHU1MzczXHU2NUY2XHU2MzA5XHU5NzAwXHU3Njg0XHU1MzlGXHU1QjUwXHU1MzE2IENTUyBcdTVGMTVcdTY0Q0UgVW5vQ1NTXHJcbmltcG9ydCB7IHJlZ2lzdGVyQXV0b0ltcG9ydCwgcmVnaXN0ZXJBdXRvQ29tcG9uZW50cyB9IGZyb20gJy4vYXV0by1pbXBvcnQtcGx1Z2luJ1xyXG5pbXBvcnQgeyByZWdpc3Rlckh0bWxQbHVnaW4gfSBmcm9tICcuL2NvbXBpbGUuaHRtbCdcclxuaW1wb3J0IHsgcmVnaXN0ZXJJbWFnZU1pbmkgfSBmcm9tICcuL2ltYWdlLm1pbmknXHJcbmltcG9ydCB7IHJlZ2lzdGVyU3ZnSWNvbnMgfSBmcm9tICcuL3JlZ2lzdGVyLXN2Zy1pY29ucydcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiBnZW5lcmF0ZVZpdGVQbHVnaW5zKHZpdGVFbnY6IFZpdGVFbnYsIGlzQnVpbGQ6IGJvb2xlYW4pOiBQbHVnaW5PcHRpb25bXSB7XHJcbiAgY29uc3QgcGx1Z2luczogUGx1Z2luT3B0aW9uW10gPSBbXVxyXG5cclxuICAvKiogXHU2M0QwXHU0RjlCIFZ1ZSAzIFx1NTM1NVx1NjU4N1x1NEVGNlx1N0VDNFx1NEVGNlx1NjUyRlx1NjMwMSAqL1xyXG4gIHBsdWdpbnMucHVzaCh2dWUoeyBpbmNsdWRlOiBbL1xcLnZ1ZSQvXSB9KSlcclxuXHJcbiAgLyoqIFx1NjNEMFx1NEY5QiBWdWUgMyBKU1gvVFNYIFx1NjUyRlx1NjMwMSAqL1xyXG4gIHBsdWdpbnMucHVzaCh2dWVKc3goKSlcclxuXHJcbiAgLyoqIFx1NjNEMFx1NEY5QiBTdmcgXHU1NkZFXHU2ODA3XHU1MjlGXHU4MEZEXHU3Njg0XHU0RjdGXHU3NTI4XHU2NTJGXHU2MzAxICovXHJcbiAgcGx1Z2lucy5wdXNoKHJlZ2lzdGVyU3ZnSWNvbnMoKSlcclxuXHJcbiAgLyoqIFx1NTM3M1x1NjVGNlx1NjMwOVx1OTcwMFx1NzY4NFx1NTM5Rlx1NUI1MFx1NTMxNiBDU1MgXHU1RjE1XHU2NENFIFVub0NTUyAqL1xyXG4gIHBsdWdpbnMucHVzaChVbm9DU1MoKSlcclxuXHJcbiAgLyoqIFx1ODFFQVx1NTJBOFx1OERERlx1OEUyQVx1NEY5RFx1OEQ1Nlx1NUU3Nlx1NUJGQ1x1NTE2NVx1NjI0MFx1OTcwMFx1NzY4NFx1NTE4NVx1NUJCOVx1RkYwQ1x1OTA3Rlx1NTE0RFx1NjI0Qlx1NTJBOFx1NUJGQ1x1NTE2NVx1NzY4NFx1N0U0MVx1NzQxMFx1NkI2NVx1OUFBNCAqL1xyXG4gIHBsdWdpbnMucHVzaChyZWdpc3RlckF1dG9JbXBvcnQoKSlcclxuXHJcbiAgLyoqIFx1NjNEMFx1NEY5Qlx1N0VDNFx1NEVGNlx1ODFFQVx1NTJBOFx1NjMwOVx1OTcwMFx1NUJGQ1x1NTE2NVx1NTNDQVx1N0M3Qlx1NTc4Qlx1NThGMFx1NjYwRVx1NTI5Rlx1ODBGRCAqL1xyXG4gIHBsdWdpbnMucHVzaChyZWdpc3RlckF1dG9Db21wb25lbnRzKCkpXHJcblxyXG4gIC8qKiBcdTk0ODhcdTVCRjkgaW5kZXguaHRtbFx1RkYwQ1x1NjNEMFx1NEY5Qlx1NTM4Qlx1N0YyOVx1NTQ4Q1x1NTdGQVx1NEU4RSBlanMgXHU2QTIxXHU2NzdGXHU1MjlGXHU4MEZEXHVGRjBDXHU0RUE2XHU1M0VGXHU1QkY5XHU1MTc2XHU2Q0U4XHU1MTY1XHU1MkE4XHU2MDAxXHU2NTcwXHU2MzZFICovXHJcbiAgcGx1Z2lucy5wdXNoKHJlZ2lzdGVySHRtbFBsdWdpbih2aXRlRW52LCBpc0J1aWxkKSlcclxuXHJcbiAgaWYgKGlzQnVpbGQpIHtcclxuICAgIC8qKiBcdTU2RkVcdTcyNDdcdTUzOEJcdTdGMjlcdTYzRDJcdTRFRjZcdUZGMENcdTRGN0ZcdTc1MjhcdTdCODBcdTUzNTVcdUZGMENcdTkxQ0RcdTg5ODFcdTc2ODRcdTY2MkZcdTVCODNcdTc3MUZcdTc2ODRcdTVGODhcdTVGRUIgXHVEODNEXHVERTgwXHVEODNEXHVERTgwXHVEODNEXHVERTgwXHVEODNEXHVERTgwICovXHJcbiAgICBwbHVnaW5zLnB1c2gocmVnaXN0ZXJJbWFnZU1pbmkoKSlcclxuICB9XHJcblxyXG4gIHJldHVybiBwbHVnaW5zXHJcbn1cclxuIiwgImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFwxNTg5OVxcXFxEb2N1bWVudHNcXFxcdnNjb2RlU3BhY2VcXFxcc3VuXFxcXHN1bkN1cnJlbmN5XFxcXGJ1aWxkXFxcXHBsdWdpbnNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZmlsZW5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXDE1ODk5XFxcXERvY3VtZW50c1xcXFx2c2NvZGVTcGFjZVxcXFxzdW5cXFxcc3VuQ3VycmVuY3lcXFxcYnVpbGRcXFxccGx1Z2luc1xcXFxhdXRvLWltcG9ydC1wbHVnaW4udHNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L1VzZXJzLzE1ODk5L0RvY3VtZW50cy92c2NvZGVTcGFjZS9zdW4vc3VuQ3VycmVuY3kvYnVpbGQvcGx1Z2lucy9hdXRvLWltcG9ydC1wbHVnaW4udHNcIjtpbXBvcnQgdHlwZSB7IFBsdWdpbk9wdGlvbiB9IGZyb20gJ3ZpdGUnXHJcbmltcG9ydCBBdXRvSW1wb3J0IGZyb20gJ3VucGx1Z2luLWF1dG8taW1wb3J0L3ZpdGUnXHJcbmltcG9ydCBBdXRvQ29tcG9uZW50cyBmcm9tICd1bnBsdWdpbi12dWUtY29tcG9uZW50cy92aXRlJ1xyXG5pbXBvcnQgeyBBbnREZXNpZ25WdWVSZXNvbHZlciB9IGZyb20gJ3VucGx1Z2luLXZ1ZS1jb21wb25lbnRzL3Jlc29sdmVycydcclxuXHJcbmV4cG9ydCBjb25zdCByZWdpc3RlckF1dG9JbXBvcnQgPSAoKTogUGx1Z2luT3B0aW9uID0+IHtcclxuICByZXR1cm4gQXV0b0ltcG9ydCh7XHJcbiAgICBpbXBvcnRzOiBbJ3Z1ZScsICdwaW5pYScsICd2dWUtcm91dGVyJywndnVlLWkxOG4nXSwgLy8gXHU4MUVBXHU1MkE4XHU1QkZDXHU1MTY1IHZ1ZVx1MzAwMXZ1ZS1yb3V0ZXJcdTMwMDFQaW5pYSBcdTc2RjhcdTUxNzNcdTUxRkRcdTY1NzBcclxuICAgIGR0czogJ3R5cGVzL2F1dG8tZ2VuZXJhdGUvYXV0by1pbXBvcnQuZC50cycsXHJcbiAgICBkaXJzOiBbJ3NyYy9zdG9yZS9tb2R1bGVzJywgJ3NyYy9ob29rcyddLCAvLyBcdTkxNERcdTdGNkVcdTUxNzZcdTVCODNcdTk3MDBcdTg5ODFcdTVCRkNcdTUxNjVcdTc2ODRcdTY1ODdcdTRFRjZcdTc2RUVcdTVGNTVcclxuICB9KVxyXG59XHJcblxyXG5leHBvcnQgY29uc3QgcmVnaXN0ZXJBdXRvQ29tcG9uZW50cyA9ICgpOiBQbHVnaW5PcHRpb24gPT4ge1xyXG4gIHJldHVybiBBdXRvQ29tcG9uZW50cyh7XHJcbiAgICByZXNvbHZlcnM6IFtBbnREZXNpZ25WdWVSZXNvbHZlcih7IGltcG9ydFN0eWxlOiBmYWxzZSB9KV0sXHJcbiAgICBkdHM6ICd0eXBlcy9hdXRvLWdlbmVyYXRlL2F1dG8tY29tcG9uZW50cy5kLnRzJyxcclxuICAgIGRpcnM6IFsnc3JjL2NvbXBvbmVudHMnXSwgLy8gXHU5MTREXHU3RjZFXHU1MTc2XHU1QjgzXHU5NzAwXHU4OTgxXHU1QkZDXHU1MTY1XHU3Njg0XHU2NTg3XHU0RUY2XHU3NkVFXHU1RjU1XHJcbiAgfSlcclxufVxyXG4iLCAiY29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2Rpcm5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXDE1ODk5XFxcXERvY3VtZW50c1xcXFx2c2NvZGVTcGFjZVxcXFxzdW5cXFxcc3VuQ3VycmVuY3lcXFxcYnVpbGRcXFxccGx1Z2luc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxcVXNlcnNcXFxcMTU4OTlcXFxcRG9jdW1lbnRzXFxcXHZzY29kZVNwYWNlXFxcXHN1blxcXFxzdW5DdXJyZW5jeVxcXFxidWlsZFxcXFxwbHVnaW5zXFxcXGNvbXBpbGUuaHRtbC50c1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQzovVXNlcnMvMTU4OTkvRG9jdW1lbnRzL3ZzY29kZVNwYWNlL3N1bi9zdW5DdXJyZW5jeS9idWlsZC9wbHVnaW5zL2NvbXBpbGUuaHRtbC50c1wiO2ltcG9ydCBkYXlqcyBmcm9tICdkYXlqcydcclxuaW1wb3J0IHR5cGUgeyBQbHVnaW5PcHRpb24gfSBmcm9tICd2aXRlJ1xyXG5pbXBvcnQgeyBjcmVhdGVIdG1sUGx1Z2luIH0gZnJvbSAndml0ZS1wbHVnaW4taHRtbCdcclxuXHJcbmV4cG9ydCBjb25zdCByZWdpc3Rlckh0bWxQbHVnaW4gPSAodml0ZUVudjogVml0ZUVudiwgaXNCdWlsZDogYm9vbGVhbik6IFBsdWdpbk9wdGlvbiA9PiB7XHJcbiAgY29uc3QgeyBWSVRFX0FQUF9USVRMRSB9ID0gdml0ZUVudlxyXG5cclxuICByZXR1cm4gY3JlYXRlSHRtbFBsdWdpbih7XHJcbiAgICAvLyBcdTU3MjhcdThGRDlcdTkxQ0NcdTUxOTkgZW50cnkgXHU1NDBFXHVGRjBDXHU0RjYwXHU1QzA2XHU0RTBEXHU5NzAwXHU4OTgxXHU1NzI4IGBpbmRleC5odG1sYCBcdTUxODVcdTZERkJcdTUyQTAgc2NyaXB0IFx1NjgwN1x1N0I3RVx1RkYwQ1x1NTM5Rlx1NjcwOVx1NjgwN1x1N0I3RVx1OTcwMFx1ODk4MVx1NTIyMFx1OTY2NFxyXG4gICAgZW50cnk6ICdzcmMvbWFpbi50cycsXHJcbiAgICAvLyBcdTY2MkZcdTU0MjZcdTUzOEJcdTdGMjkgaHRtbFxyXG4gICAgbWluaWZ5OiBpc0J1aWxkLFxyXG4gICAgLy8gXHU5NzAwXHU4OTgxXHU2Q0U4XHU1MTY1IGluZGV4Lmh0bWwgZWpzIFx1NkEyMVx1NzI0OFx1NzY4NFx1NjU3MFx1NjM2RVxyXG4gICAgaW5qZWN0OiB7XHJcbiAgICAgIGRhdGE6IHsgVklURV9BUFBfVElUTEUsIGJ1aWxkVGltZTogZGF5anMoKS5mb3JtYXQoYFlZWVktTU0tREQgSEg6bW06c3NgKSB9LFxyXG4gICAgfSxcclxuICB9KVxyXG59XHJcbiIsICJjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfZGlybmFtZSA9IFwiQzpcXFxcVXNlcnNcXFxcMTU4OTlcXFxcRG9jdW1lbnRzXFxcXHZzY29kZVNwYWNlXFxcXHN1blxcXFxzdW5DdXJyZW5jeVxcXFxidWlsZFxcXFxwbHVnaW5zXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFwxNTg5OVxcXFxEb2N1bWVudHNcXFxcdnNjb2RlU3BhY2VcXFxcc3VuXFxcXHN1bkN1cnJlbmN5XFxcXGJ1aWxkXFxcXHBsdWdpbnNcXFxcaW1hZ2UubWluaS50c1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQzovVXNlcnMvMTU4OTkvRG9jdW1lbnRzL3ZzY29kZVNwYWNlL3N1bi9zdW5DdXJyZW5jeS9idWlsZC9wbHVnaW5zL2ltYWdlLm1pbmkudHNcIjtpbXBvcnQgbWluaXBpYyBmcm9tICd2aXRlLXBsdWdpbi1pbWFnZW1pbidcclxuXHJcbmV4cG9ydCBjb25zdCByZWdpc3RlckltYWdlTWluaSA9ICgpID0+IHtcclxuICByZXR1cm4gbWluaXBpYyh7XHJcbiAgICAvKiogXHU2NUUwXHU2MzVGXHU1MzhCXHU3RjI5XHU5MTREXHU3RjZFXHVGRjBDXHU2NUUwXHU2MzVGXHU1MzhCXHU3RjI5XHU0RTBCXHU1NkZFXHU3MjQ3XHU4RDI4XHU5MUNGXHU0RTBEXHU0RjFBXHU1M0Q4XHU1REVFICovXHJcbiAgICBvcHRpcG5nOiB7XHJcbiAgICAgIG9wdGltaXphdGlvbkxldmVsOiA3LFxyXG4gICAgfSxcclxuICAgIC8qKiBcdTY3MDlcdTYzNUZcdTUzOEJcdTdGMjlcdTkxNERcdTdGNkVcdUZGMENcdTY3MDlcdTYzNUZcdTUzOEJcdTdGMjlcdTRFMEJcdTU2RkVcdTcyNDdcdThEMjhcdTkxQ0ZcdTUzRUZcdTgwRkRcdTRGMUFcdTUzRDhcdTVERUUgKi9cclxuICAgIHBuZ3F1YW50OiB7XHJcbiAgICAgIHF1YWxpdHk6IFswLjgsIDAuOV0sIC8vIFx1NTM4Qlx1N0YyOVx1OEQyOFx1OTFDRlxyXG4gICAgICBzcGVlZDogNCwgLy8gXHU1MzhCXHU3RjI5XHU5MDFGXHU1RUE2XHVGRjBDXHU4MzAzXHU1NkY0IDB+MTFcclxuICAgIH0sXHJcbiAgICBnaWZzaWNsZToge1xyXG4gICAgICBvcHRpbWl6YXRpb25MZXZlbDogNyxcclxuICAgICAgaW50ZXJsYWNlZDogZmFsc2UsXHJcbiAgICB9LFxyXG4gICAgbW96anBlZzoge1xyXG4gICAgICBxdWFsaXR5OiAyMCwgLy8gXHU1MzhCXHU3RjI5XHU4RDI4XHU5MUNGXHJcbiAgICB9LFxyXG5cclxuICAgIHN2Z286IHtcclxuICAgICAgcGx1Z2luczogW3sgbmFtZTogJ3JlbW92ZVZpZXdCb3gnIH0sIHsgbmFtZTogJ3JlbW92ZUVtcHR5QXR0cnMnLCBhY3RpdmU6IGZhbHNlIH1dLFxyXG4gICAgfSxcclxuXHJcbiAgICAvKiogXHU2NjJGXHU1NDI2XHU3OTgxXHU3NTI4ICovXHJcbiAgICBkaXNhYmxlOiBmYWxzZSxcclxuICAgIC8qKiBcdTY2MkZcdTU0MjZcdTU3MjhcdTYzQTdcdTUyMzZcdTUzRjBcdThGOTNcdTUxRkFcdTUzOEJcdTdGMjlcdTdFRDNcdTY3OUMgKi9cclxuICAgIHZlcmJvc2U6IHRydWUsXHJcbiAgfSlcclxufVxyXG4iLCAiY29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2Rpcm5hbWUgPSBcIkM6XFxcXFVzZXJzXFxcXDE1ODk5XFxcXERvY3VtZW50c1xcXFx2c2NvZGVTcGFjZVxcXFxzdW5cXFxcc3VuQ3VycmVuY3lcXFxcYnVpbGRcXFxccGx1Z2luc1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxcVXNlcnNcXFxcMTU4OTlcXFxcRG9jdW1lbnRzXFxcXHZzY29kZVNwYWNlXFxcXHN1blxcXFxzdW5DdXJyZW5jeVxcXFxidWlsZFxcXFxwbHVnaW5zXFxcXHJlZ2lzdGVyLXN2Zy1pY29ucy50c1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9pbXBvcnRfbWV0YV91cmwgPSBcImZpbGU6Ly8vQzovVXNlcnMvMTU4OTkvRG9jdW1lbnRzL3ZzY29kZVNwYWNlL3N1bi9zdW5DdXJyZW5jeS9idWlsZC9wbHVnaW5zL3JlZ2lzdGVyLXN2Zy1pY29ucy50c1wiO2ltcG9ydCB7IGNyZWF0ZVN2Z0ljb25zUGx1Z2luIH0gZnJvbSAndml0ZS1wbHVnaW4tc3ZnLWljb25zMidcclxuaW1wb3J0IHsgcGF0aFJlc29sdmUgfSBmcm9tICcuLidcclxuXHJcbmV4cG9ydCBmdW5jdGlvbiByZWdpc3RlclN2Z0ljb25zKCkge1xyXG4gIHJldHVybiBjcmVhdGVTdmdJY29uc1BsdWdpbih7XHJcbiAgICBpY29uRGlyczogW3BhdGhSZXNvbHZlKCdzcmMvYXNzZXRzL3N2Zy1pY29ucycpXSwgLy8gXHU2MzA3XHU1QjlBXHU1NkZFXHU2ODA3XHU2NTg3XHU0RUY2XHU1OTM5XHJcbiAgICBzeW1ib2xJZDogJ2ljb24tW2Rpcl0tW25hbWVdJywgLy8gXHU2MzA3XHU1QjlBc3ltYm9sSWRcdTY4M0NcdTVGMEZcclxuICB9KVxyXG59XHJcbiJdLAog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
