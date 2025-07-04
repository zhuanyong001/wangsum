/**
 * Multi-language related operations
 */

import { i18n } from './setupI18n';
import { unref, computed } from 'vue';
import { isI18n, geti18n, seti18n, cleari18n } from '@/utils/i18n'
import { getTranslation } from './lang'

function setI18nLanguage(locale) {

  // if (i18n.mode === 'legacy') {
  //   i18n.global.locale = locale;
  // } else {
  //   (i18n.global.local).value = locale;
  // }
  seti18n(locale)
}

export function useLocale() {
  const getLocale = computed(() => geti18n());
  const getShowLocalePicker = computed(() => localeStore.getShowPicker);

  const getAntdLocale = computed(() => {
    const localeMessage = i18n.global.getLocaleMessage<{ antdLocale }>(unref(getLocale));
    return localeMessage?.antdLocale ?? {};
  });

  // Switching the language will change the locale of useI18n
  // And submit to configuration modification
  async function changeLocale(locale) {
    const globalI18n = i18n.global;
    const currentLocale = unref(globalI18n.locale);
    if (currentLocale === locale) {
      return locale;
    }

    const langModule = await getTranslation(locale);
    if (!langModule) return;

    const { message } = langModule;

    globalI18n.setLocaleMessage(locale, message);
  
    setI18nLanguage(locale);
    return locale;
  }

  return {
    getLocale,
    getShowLocalePicker,
    changeLocale,
    getAntdLocale,
  };
}
