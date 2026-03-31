import { ref, watch } from 'vue'
import { defineStore } from 'pinia'

export const useLanguageStore = defineStore('language', () => {
  // State
  const locale = ref(localStorage.getItem('mediscan_lang') || 'bn')
  
  // Reference to i18n instance (will be set from component)
  let i18nInstance = null

  // Set i18n instance
  function setI18n(i18n) {
    i18nInstance = i18n
    // Sync locale with i18n
    if (i18nInstance) {
      i18nInstance.global.locale.value = locale.value
    }
  }

  // Toggle between Bangla and English
  function toggle() {
    locale.value = locale.value === 'bn' ? 'en' : 'bn'
    updateLocale()
  }

  // Set specific locale
  function setLocale(lang) {
    if (lang !== 'bn' && lang !== 'en') {
      console.warn(`Invalid locale: ${lang}. Using 'bn' instead.`)
      lang = 'bn'
    }
    locale.value = lang
    updateLocale()
  }

  // Update locale in localStorage and i18n
  function updateLocale() {
    localStorage.setItem('mediscan_lang', locale.value)
    
    if (i18nInstance) {
      i18nInstance.global.locale.value = locale.value
    }

    // Update HTML lang attribute
    document.documentElement.lang = locale.value
  }

  // Watch locale changes and update HTML lang
  watch(locale, (newLocale) => {
    document.documentElement.lang = newLocale
  }, { immediate: true })

  return {
    // State
    locale,

    // Actions
    toggle,
    setLocale,
    setI18n
  }
})
