<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-amber-50 flex items-center justify-center p-4">
    <div class="text-center">
      <!-- Large 404 in Bangla Style -->
      <div class="mb-6">
        <h1 class="text-9xl font-bold bg-gradient-to-r from-teal-600 to-teal-400 bg-clip-text text-transparent">
          {{ locale === 'bn' ? '৪০৪' : '404' }}
        </h1>
      </div>

      <!-- Illustration -->
      <div class="mb-8">
        <svg
          class="w-48 h-48 mx-auto text-gray-300"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <!-- Document with question mark -->
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="1.5"
            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
          />
          <!-- Question mark circle -->
          <circle cx="12" cy="10" r="3" stroke-width="1.5" />
          <path
            stroke-linecap="round"
            stroke-width="2"
            d="M12 14v.01"
          />
        </svg>
      </div>

      <!-- Message -->
      <h2 class="text-2xl font-bold text-gray-800 mb-3">
        {{ $t('notFound.title') }}
      </h2>
      <p class="text-gray-600 mb-8 max-w-md mx-auto">
        {{ $t('notFound.description') }}
      </p>

      <!-- Back to Dashboard Button -->
      <router-link
        to="/dashboard"
        class="inline-flex items-center gap-2 px-6 py-3 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 transition-colors shadow-lg shadow-teal-600/30"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
          />
        </svg>
        {{ $t('notFound.back_home') }}
      </router-link>

      <!-- Additional Links -->
      <div class="mt-6 flex items-center justify-center gap-4 text-sm">
        <router-link
          to="/upload"
          class="text-teal-600 hover:text-teal-700 hover:underline"
        >
          {{ $t('nav.upload') }}
        </router-link>
        <span class="text-gray-300">|</span>
        <router-link
          to="/history"
          class="text-teal-600 hover:text-teal-700 hover:underline"
        >
          {{ $t('nav.history') }}
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()
</script>
