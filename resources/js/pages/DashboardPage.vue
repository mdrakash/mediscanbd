<script setup>
import { useI18n } from 'vue-i18n'
import { RouterLink } from 'vue-router'

const { t } = useI18n()
</script>

<template>
  <div class="max-w-4xl mx-auto">
      <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">
          {{ t('dashboard.welcome') }}
        </h1>
        <p class="text-gray-600 mb-6">
          {{ t('app.tagline') }}
        </p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <RouterLink
            to="/upload"
            class="bg-gradient-to-br from-teal-500 to-teal-600 text-white p-6 rounded-xl hover:shadow-lg transition-shadow"
          >
            <div class="flex items-center gap-4">
              <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
              </svg>
              <div>
                <h3 class="text-xl font-semibold">{{ t('dashboard.quick_upload') }}</h3>
                <p class="text-teal-50 text-sm">{{ t('upload.title') }}</p>
              </div>
            </div>
          </RouterLink>

          <RouterLink
            to="/history"
            class="bg-gradient-to-br from-amber-400 to-amber-500 text-white p-6 rounded-xl hover:shadow-lg transition-shadow"
          >
            <div class="flex items-center gap-4">
              <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <h3 class="text-xl font-semibold">{{ t('history.title') }}</h3>
                <p class="text-amber-50 text-sm">{{ t('dashboard.view_all_history') }}</p>
              </div>
            </div>
          </RouterLink>
        </div>
      </div>
    </div>
</template>
