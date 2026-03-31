<script setup>
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

const { t } = useI18n()
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-amber-50 flex items-center justify-center p-4">
    <div class="text-center">
      <div class="mb-8">
        <svg class="w-32 h-32 mx-auto text-teal-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      <h1 class="text-6xl font-bold text-gray-800 mb-4">404</h1>
      <p class="text-2xl text-gray-600 mb-8">{{ t('errors.not_found') }}</p>
      <RouterLink 
        to="/"
        class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors"
      >
        {{ t('common.back') }}
      </RouterLink>
    </div>
  </div>
</template>
