<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// Props
defineProps({
  text: {
    type: String,
    default: ''
  }
})
</script>

<template>
  <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border-l-4 border-amber-400 rounded-r-xl p-4 sm:p-5 shadow-sm">
    <div class="flex gap-3">
      <!-- Warning Icon -->
      <div class="flex-shrink-0">
        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
          <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
      </div>
      
      <!-- Content -->
      <div class="flex-1">
        <h4 class="text-sm font-semibold text-amber-800 mb-1">
          {{ t('analysis.disclaimer') }}
        </h4>
        <p class="text-sm text-amber-700 leading-relaxed">
          {{ text || t('analysis.disclaimer_text') }}
        </p>
        <p class="text-xs text-amber-600 mt-2 font-medium">
          {{ t('analysis.disclaimer_short') }}
        </p>
      </div>
    </div>
  </div>
</template>
