import { createI18n } from 'vue-i18n'
import bn from './bn.json'
import en from './en.json'

const i18n = createI18n({
  legacy: false,
  locale: localStorage.getItem('mediscan_lang') || 'bn',
  fallbackLocale: 'en',
  messages: {
    bn,
    en
  }
})

export default i18n
