<script setup>
import { useLanguageStore } from '../../stores/language'

const languageStore = useLanguageStore()
</script>

<template>
  <div class="relative inline-flex bg-white/90 backdrop-blur-sm rounded-full p-1 shadow-md border border-teal-200">
    <!-- Background slider -->
    <div 
      class="absolute top-1 bottom-1 w-[calc(50%-4px)] bg-teal-600 rounded-full transition-transform duration-300 ease-in-out shadow-sm"
      :class="languageStore.locale === 'en' ? 'translate-x-full' : 'translate-x-0'"
    ></div>

    <!-- Bangla button -->
    <button
      @click="() => languageStore.setLocale('bn')"
      class="relative z-10 px-4 py-1.5 rounded-full font-semibold text-sm transition-colors duration-300 min-w-[60px]"
      :class="languageStore.locale === 'bn' ? 'text-white' : 'text-teal-700 hover:text-teal-800'"
    >
      বাং
    </button>

    <!-- English button -->
    <button
      @click="() => languageStore.setLocale('en')"
      class="relative z-10 px-4 py-1.5 rounded-full font-semibold text-sm transition-colors duration-300 min-w-[60px]"
      :class="languageStore.locale === 'en' ? 'text-white' : 'text-teal-700 hover:text-teal-800'"
    >
      EN
    </button>
  </div>
</template>
