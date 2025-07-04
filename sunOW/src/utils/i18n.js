const i18nKey = 'i18nKey'

const isI18n = () => {
  return !!localStorage.getItem(i18nKey)
}

const geti18n = () => {
  return localStorage.getItem(i18nKey)
}

const seti18n = (i18n) => {
  localStorage.setItem(i18nKey, i18n)
}

const cleari18n = () => {
  localStorage.removeItem(i18nKey)
}

export { isI18n, geti18n, seti18n, cleari18n }
