// src/locales/index.js

// 创建一个缓存对象，避免重复加载同一个文件
const cache = {};

export async function getTranslation(locale) {
  // 如果缓存中有翻译数据，直接返回
  if (cache[locale]) {
    return cache[locale];
  }

  // 根据传入的语言代码加载对应的 JSON 文件
  try {
    const translation = await import(`./${locale}.json`);
    cache[locale] = translation.default || translation; // 将翻译数据存入缓存
    return cache[locale];
  } catch (error) {
    // 如果加载失败，返回一个默认的英文翻译
    console.error(`Failed to load translation file for locale: ${locale}`, error);
    return cache['en'] || {}; // 默认返回英文翻译
  }
}
