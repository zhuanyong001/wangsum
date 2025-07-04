// module.exports = {
//     plugins: {
//       'postcss-px-to-viewport': {
//         unitToConvert: 'px',
//         viewportWidth: 1920, // 视口宽度，根据设计稿来设定
//         // viewportHeight: 667, // 可选，视口高度（如果需要）
//         unitPrecision: 5, // 转换后的精度
//         propList: ['*'], // 可以转换的属性列表，*表示所有
//         selectorBlackList: ['.ignore', '.hairlines'], // 排除特定类名
//         minPixelValue: 1, // 小于或等于1px的单位不转换
//         mediaQuery: false, // 是否允许在媒体查询中转换
//         exclude: /(node_module)/, // 排除node_modules中的文件
//       },
//     },
//   };
  module.exports = {
    plugins: {
      autoprefixer: {},
      'postcss-pxtorem': {
        rootValue: 16, // 根元素字体大小
        propList: ['*'], // 转换所有 CSS 属性
        unitPrecision: 5, // 精度
        minPixelValue: 1, // 小于等于 1px 不转换
        selectorBlackList: ['.ignore'], // 忽略的选择器类名
      },
    },
  };
  