<script setup>
import { RouterView } from 'vue-router'
import { useI18n } from 'vue-i18n'
import LanguageToggle from '../components/common/LanguageToggle.vue'

const { t } = useI18n()
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-teal-600 via-teal-500 to-amber-400 flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Animated background circles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute top-20 left-20 w-72 h-72 bg-white/10 rounded-full blur-3xl animate-pulse"></div>
      <div class="absolute bottom-20 right-20 w-96 h-96 bg-amber-300/20 rounded-full blur-3xl animate-pulse delay-700"></div>
      <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-teal-400/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
    </div>

    <!-- Language toggle -->
    <div class="absolute top-6 right-6 z-20">
      <LanguageToggle />
    </div>

    <!-- Main content -->
    <div class="w-full max-w-6xl grid md:grid-cols-2 gap-8 items-center relative z-10">
      <!-- Left panel - Branding (hidden on mobile) -->
      <div class="hidden md:block text-white">
        <!-- Logo with medical icon -->
        <div class="mb-8">
          <div class="flex items-center gap-4 mb-6">
            <div class="w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center shadow-2xl">
              <!-- Heart + Stethoscope combined icon -->
              <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </div>
            <div>
              <h1 class="text-4xl font-bold drop-shadow-lg">{{ t('app.name') }}</h1>
              <p class="text-teal-100 text-lg mt-1">{{ t('app.tagline') }}</p>
            </div>
          </div>
        </div>

        <!-- Features list -->
        <div class="space-y-4 bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-lg">{{ t('login.feature1_title') }}</h3>
              <p class="text-teal-100 text-sm">{{ t('login.feature1_desc') }}</p>
            </div>
          </div>

          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-lg">{{ t('login.feature2_title') }}</h3>
              <p class="text-teal-100 text-sm">{{ t('login.feature2_desc') }}</p>
            </div>
          </div>

          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-lg">{{ t('login.feature3_title') }}</h3>
              <p class="text-teal-100 text-sm">{{ t('login.feature3_desc') }}</p>
            </div>
          </div>

          <div class="flex items-start gap-4">
            <div class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-lg">{{ t('login.feature4_title') }}</h3>
              <p class="text-teal-100 text-sm">{{ t('login.feature4_desc') }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Right panel - Login card -->
      <div class="w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10 backdrop-blur-sm">
          <!-- Mobile logo -->
          <div class="md:hidden text-center mb-6">
            <div class="inline-flex items-center gap-3 mb-3">
              <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </div>
              <h1 class="text-2xl font-bold text-teal-600">{{ t('app.name') }}</h1>
            </div>
            <p class="text-gray-600 text-sm">{{ t('app.tagline') }}</p>
          </div>

          <RouterView />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes pulse {
  0%, 100% {
    opacity: 0.3;
    transform: scale(1);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.05);
  }
}

.animate-pulse {
  animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.delay-700 {
  animation-delay: 700ms;
}

.delay-1000 {
  animation-delay: 1000ms;
}
</style>
