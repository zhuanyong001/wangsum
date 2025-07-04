

import { createI18n } from 'vue-i18n';
import { localeSetting } from '@/settings/localeSetting';
import { isI18n, geti18n, seti18n, cleari18n } from '@/utils/i18n'
const { fallback, availableLocales } = localeSetting;
import { getTranslation } from './lang'
export let i18n;

async function createI18nOptions(){
  const locale = geti18n() || 'zh_CN';
  const defaultLocal = await getTranslation(locale);
  console.log(locale,{
    [locale]: defaultLocal,
  })
  return {
    legacy: false,
    locale,
    fallbackLocale: 'en',
    messages: {
      [locale]: defaultLocal,
    },
    availableLocales: availableLocales,
    sync: true, //If you don’t want to inherit locale from global scope, you need to set sync of i18n component option to false.
    silentTranslationWarn: true, // true - warning off
    missingWarn: false,
    silentFallbackWarn: true,
  };
}

// setup i18n instance with glob
export async function setupI18n(app) {
  const options = await createI18nOptions();
  i18n = createI18n(options);
  app.use(i18n);
}
