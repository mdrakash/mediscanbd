<script setup>
import { useI18n } from 'vue-i18n'
import { ShieldCheckIcon } from '@heroicons/vue/24/outline'

const { t } = useI18n()

const handleGoogleLogin = () => {
  window.location.href = '/auth/google'
}
</script>

<template>
  <!-- Welcome text -->
  <div class="text-center mb-8">
    <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
      {{ t('auth.welcome') }}
    </h2>
    <p class="text-gray-600">
      {{ t('auth.login_desc') }}
    </p>
  </div>

    <!-- Google Login Button -->
    <button
      @click="handleGoogleLogin"
      class="w-full bg-white border-2 border-gray-300 hover:border-teal-500 hover:shadow-lg text-gray-800 font-semibold py-4 px-6 rounded-xl flex items-center justify-center gap-4 transition-all duration-300 group relative overflow-hidden"
    >
      <!-- Hover gradient background -->
      <div class="absolute inset-0 bg-gradient-to-r from-teal-50 to-amber-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      
      <!-- Google logo SVG -->
      <svg class="w-6 h-6 relative z-10 flex-shrink-0" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
      </svg>
      
      <span class="relative z-10 text-base">{{ t('auth.google_login') }}</span>
    </button>

    <!-- Divider -->
    <div class="relative my-8">
      <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-gray-200"></div>
      </div>
      <div class="relative flex justify-center text-sm">
        <span class="px-4 bg-white text-gray-500">{{ t('login.secure_login') }}</span>
      </div>
    </div>

    <!-- Privacy note -->
    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4 flex items-start gap-3">
      <ShieldCheckIcon class="w-6 h-6 text-teal-600 flex-shrink-0 mt-0.5" />
      <div>
        <p class="text-sm font-semibold text-teal-900 mb-1">{{ t('login.privacy_title') }}</p>
        <p class="text-xs text-teal-700 leading-relaxed">
          {{ t('login.privacy_note') }}
        </p>
      </div>
    </div>

    <!-- Features showcase (mobile) -->
    <div class="md:hidden mt-8 space-y-3">
      <div class="flex items-center gap-3 text-sm text-gray-700">
        <div class="w-6 h-6 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
        </div>
        <span class="font-medium">{{ t('login.feature1_title') }}</span>
      </div>
      <div class="flex items-center gap-3 text-sm text-gray-700">
        <div class="w-6 h-6 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
        </div>
        <span class="font-medium">{{ t('login.feature2_title') }}</span>
      </div>
      <div class="flex items-center gap-3 text-sm text-gray-700">
        <div class="w-6 h-6 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
        </div>
        <span class="font-medium">{{ t('login.feature3_title') }}</span>
      </div>
      <div class="flex items-center gap-3 text-sm text-gray-700">
        <div class="w-6 h-6 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
          <svg class="w-4 h-4 text-teal-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
          </svg>
        </div>
        <span class="font-medium">{{ t('login.feature4_title') }}</span>
      </div>
    </div>
</template>
