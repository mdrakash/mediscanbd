<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// Props
const props = defineProps({
  medicine: {
    type: Object,
    required: true
  },
  index: {
    type: Number,
    required: true
  }
})

// State
const isExpanded = ref(false)

// Computed - normalize arrays (handle string vs array from AI)
const sideEffects = computed(() => {
  const effects = props.medicine.side_effects
  if (!effects) return []
  if (Array.isArray(effects)) return effects
  // If string, split by common delimiters or wrap in array
  if (typeof effects === 'string') {
    // Try splitting by common separators
    if (effects.includes(',')) return effects.split(',').map(s => s.trim())
    if (effects.includes('।')) return effects.split('।').map(s => s.trim()).filter(Boolean)
    return [effects]
  }
  return []
})

const warnings = computed(() => {
  const warns = props.medicine.warnings
  if (!warns) return []
  if (Array.isArray(warns)) return warns
  // If string, split by common delimiters or wrap in array
  if (typeof warns === 'string') {
    // Try splitting by common separators
    if (warns.includes(',')) return warns.split(',').map(s => s.trim())
    if (warns.includes('।')) return warns.split('।').map(s => s.trim()).filter(Boolean)
    return [warns]
  }
  return []
})

// Methods
function toggleExpanded() {
  isExpanded.value = !isExpanded.value
}
</script>

<template>
  <div class="bg-white rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
    <!-- Header -->
    <div class="p-4 sm:p-5 border-b border-gray-100">
      <div class="flex items-start gap-3">
        <!-- Index Badge -->
        <span class="flex-shrink-0 w-8 h-8 bg-teal-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
          {{ index }}
        </span>
        
        <!-- Medicine Name -->
        <div class="flex-1">
          <h3 class="text-lg font-bold text-gray-800 leading-tight">
            {{ medicine.name }}
          </h3>
          <p v-if="medicine.generic_name" class="text-sm text-gray-500 mt-0.5">
            {{ medicine.generic_name }}
          </p>
        </div>
      </div>
    </div>

    <!-- Details Grid -->
    <div class="p-4 sm:p-5">
      <!-- Dosage Info Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
        <!-- Dosage -->
        <div v-if="medicine.dosage" class="flex items-start gap-2">
          <span class="text-lg">💊</span>
          <div>
            <p class="text-xs text-gray-500">{{ t('analysis.dosage') }}</p>
            <p class="text-sm font-medium text-gray-800">{{ medicine.dosage }}</p>
          </div>
        </div>

        <!-- Frequency -->
        <div v-if="medicine.frequency" class="flex items-start gap-2">
          <span class="text-lg">🕐</span>
          <div>
            <p class="text-xs text-gray-500">{{ t('analysis.frequency') }}</p>
            <p class="text-sm font-medium text-gray-800">{{ medicine.frequency }}</p>
          </div>
        </div>

        <!-- Duration -->
        <div v-if="medicine.duration" class="flex items-start gap-2">
          <span class="text-lg">📅</span>
          <div>
            <p class="text-xs text-gray-500">{{ t('analysis.duration') }}</p>
            <p class="text-sm font-medium text-gray-800">{{ medicine.duration }}</p>
          </div>
        </div>

        <!-- Instructions -->
        <div v-if="medicine.instructions" class="flex items-start gap-2">
          <span class="text-lg">🍽️</span>
          <div>
            <p class="text-xs text-gray-500">{{ t('analysis.instructions') }}</p>
            <p class="text-sm font-medium text-gray-800">{{ medicine.instructions }}</p>
          </div>
        </div>
      </div>

      <!-- Purpose Section -->
      <div v-if="medicine.purpose" class="bg-teal-50 rounded-lg p-3 border border-teal-100 mb-4">
        <p class="text-xs text-teal-600 font-medium mb-1">{{ t('analysis.purpose') }}</p>
        <p class="text-sm text-teal-800">{{ medicine.purpose }}</p>
      </div>

      <!-- BD Brands -->
      <div v-if="medicine.bd_brands && medicine.bd_brands.length > 0" class="mb-4">
        <p class="text-xs text-gray-500 mb-2">{{ t('analysis.bd_brands') }}</p>
        <div class="flex flex-wrap gap-2">
          <span
            v-for="(brand, i) in medicine.bd_brands"
            :key="i"
            class="px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-md"
          >
            {{ brand }}
          </span>
        </div>
      </div>

      <!-- Price Range -->
      <p v-if="medicine.price_range" class="text-sm mb-4">
        <span class="text-gray-500">{{ t('analysis.price_range') }}: </span>
        <strong class="text-gray-700">{{ medicine.price_range }}</strong>
      </p>

      <!-- Expandable Section Toggle -->
      <button
        v-if="sideEffects.length > 0 || warnings.length > 0"
        @click="toggleExpanded"
        type="button"
        class="w-full flex items-center justify-center gap-2 py-2 text-sm text-teal-600 hover:text-teal-700 hover:bg-teal-50 rounded-lg transition-colors"
      >
        <span>{{ isExpanded ? t('analysis.show_less') : t('analysis.show_more') }}</span>
        <svg
          :class="['w-4 h-4 transition-transform', isExpanded ? 'rotate-180' : '']"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </button>

      <!-- Expandable Content -->
      <div v-if="isExpanded" class="mt-3">
        <!-- Side Effects -->
        <div v-if="sideEffects.length > 0" class="bg-red-50 rounded-lg p-4 border border-red-100 mb-3">
          <p class="text-sm text-red-600 font-semibold mb-2">⚠️ {{ t('analysis.side_effects') }}</p>
          <ul class="list-disc pl-5 space-y-1">
            <li v-for="(effect, i) in sideEffects" :key="i" class="text-sm text-red-700">
              {{ effect }}
            </li>
          </ul>
        </div>

        <!-- Warnings -->
        <div v-if="warnings.length > 0" class="bg-orange-50 rounded-lg p-4 border border-orange-200">
          <p class="text-sm text-orange-600 font-semibold mb-2">🚫 {{ t('analysis.warnings') }}</p>
          <ul class="list-disc pl-5 space-y-1">
            <li v-for="(warning, i) in warnings" :key="i" class="text-sm text-orange-700">
              {{ warning }}
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>
