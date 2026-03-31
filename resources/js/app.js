import './bootstrap'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import i18n from './i18n'
import App from './App.vue'

// Import Tailwind CSS
import '../css/app.css'

const app = createApp(App)

const pinia = createPinia()

app.use(pinia)
app.use(router)
app.use(i18n)

// Initialize language store with i18n instance
import { useLanguageStore } from './stores/language'
const languageStore = useLanguageStore()
languageStore.setI18n(i18n)

app.mount('#app')
