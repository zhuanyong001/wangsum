
// src/locale.js

export const LOCALE = {
  EN_US: 'en',
  ZH_CN: 'zh_CN',
  ZH_TW: 'zh_TW',
  JA_JP: 'ja',
  KO_KR: 'ko',
};


// src/locale.js

export const localeSetting = {
  showPicker: true,
  // 当前语言
  locale: LOCALE.EN_US,
  // 默认语言
  fallback: LOCALE.EN_US,
  // 可用语言列表
  availableLocales: [
    LOCALE.EN_US,
    LOCALE.ZH_CN,
    LOCALE.ZH_TW,
    LOCALE.JA_JP,
    LOCALE.KO_KR,
  ],
};


// src/locale.js

export const localeList = [
  {
    text: 'English',
    event: LOCALE.EN_US,
  },
  {
    text: '简体中文',
    event: LOCALE.ZH_CN,
  },
  {
    text: '繁體中文',
    event: LOCALE.ZH_TW,
  },
  {
    text: '日本語',
    event: LOCALE.JA_JP,
  },
  {
    text: '한국어',
    event: LOCALE.KO_KR,
  },
];

