<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// Props
defineProps({
  toEat: {
    type: Array,
    default: () => []
  },
  toAvoid: {
    type: Array,
    default: () => []
  }
})
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <!-- Foods to Eat -->
    <div v-if="toEat && toEat.length > 0" class="bg-green-50 rounded-xl p-4 border border-green-200">
      <div class="flex items-center gap-2 mb-3">
        <span class="text-xl">✅</span>
        <h4 class="font-semibold text-green-800">{{ t('analysis.foods_to_eat') }}</h4>
      </div>
      
      <ul class="space-y-2">
        <li
          v-for="(food, index) in toEat"
          :key="index"
          class="flex items-start gap-2 text-sm text-green-700"
        >
          <svg class="w-4 h-4 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          <span>{{ food }}</span>
        </li>
      </ul>
    </div>

    <!-- Foods to Avoid -->
    <div v-if="toAvoid && toAvoid.length > 0" class="bg-red-50 rounded-xl p-4 border border-red-200">
      <div class="flex items-center gap-2 mb-3">
        <span class="text-xl">❌</span>
        <h4 class="font-semibold text-red-800">{{ t('analysis.foods_to_avoid') }}</h4>
      </div>
      
      <ul class="space-y-2">
        <li
          v-for="(food, index) in toAvoid"
          :key="index"
          class="flex items-start gap-2 text-sm text-red-700"
        >
          <svg class="w-4 h-4 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
          <span>{{ food }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>
