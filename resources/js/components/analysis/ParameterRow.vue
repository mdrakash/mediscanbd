<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// Props
const props = defineProps({
  parameter: {
    type: Object,
    required: true
  }
})

// State
const isExpanded = ref(false)

// Computed
const statusConfig = computed(() => {
  const status = props.parameter.status?.toLowerCase() || 'normal'
  
  const configs = {
    normal: {
      label: t('analysis.normal'),
      bgClass: 'bg-green-100',
      textClass: 'text-green-700',
      borderClass: 'border-green-200',
      icon: null,
      pulse: false
    },
    high: {
      label: t('analysis.high'),
      bgClass: 'bg-red-100',
      textClass: 'text-red-700',
      borderClass: 'border-red-200',
      icon: '↑',
      pulse: false
    },
    low: {
      label: t('analysis.low'),
      bgClass: 'bg-blue-100',
      textClass: 'text-blue-700',
      borderClass: 'border-blue-200',
      icon: '↓',
      pulse: false
    },
    critical: {
      label: t('analysis.critical'),
      bgClass: 'bg-red-500',
      textClass: 'text-white',
      borderClass: 'border-red-500',
      icon: '⚠️',
      pulse: true
    }
  }
  
  return configs[status] || configs.normal
})

const hasExplanation = computed(() => {
  return props.parameter.plain_explanation && props.parameter.plain_explanation.trim()
})

// Methods
function toggleExpanded() {
  if (hasExplanation.value) {
    isExpanded.value = !isExpanded.value
  }
}
</script>

<template>
  <!-- Desktop Table Row -->
  <div class="hidden sm:contents">
    <tr
      :class="[
        'border-b border-gray-100 transition-colors',
        hasExplanation ? 'cursor-pointer hover:bg-gray-50' : '',
        isExpanded ? 'bg-gray-50' : ''
      ]"
      @click="toggleExpanded"
    >
      <!-- Parameter Name -->
      <td class="py-3 px-4">
        <div class="flex items-center gap-2">
          <span class="font-medium text-gray-800">{{ parameter.name }}</span>
          <svg
            v-if="hasExplanation"
            :class="['w-4 h-4 text-gray-400 transition-transform', isExpanded ? 'rotate-180' : '']"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </div>
      </td>
      
      <!-- Value + Unit -->
      <td class="py-3 px-4">
        <span class="font-bold text-gray-900">{{ parameter.value }}</span>
        <span v-if="parameter.unit" class="text-gray-500 text-sm ml-1">{{ parameter.unit }}</span>
      </td>
      
      <!-- Normal Range -->
      <td class="py-3 px-4 text-gray-500 text-sm">
        {{ parameter.normal_range || '-' }}
      </td>
      
      <!-- Status Badge -->
      <td class="py-3 px-4">
        <span
          :class="[
            'inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium',
            statusConfig.bgClass,
            statusConfig.textClass,
            statusConfig.pulse ? 'animate-pulse' : ''
          ]"
        >
          <span v-if="statusConfig.icon">{{ statusConfig.icon }}</span>
          {{ statusConfig.label }}
        </span>
      </td>
    </tr>
    
    <!-- Expanded Explanation Row -->
    <tr v-if="isExpanded && hasExplanation">
      <td colspan="4" class="bg-blue-50 px-4 py-3 border-b border-gray-100">
        <div class="flex items-start gap-2">
          <span class="text-blue-500">💡</span>
          <div>
            <p class="text-xs text-blue-600 font-medium mb-1">{{ t('analysis.plain_explanation') }}</p>
            <p class="text-sm text-blue-800">{{ parameter.plain_explanation }}</p>
          </div>
        </div>
      </td>
    </tr>
  </div>

  <!-- Mobile Card -->
  <div
    class="sm:hidden bg-white rounded-lg border border-gray-200 overflow-hidden"
    :class="[hasExplanation ? 'cursor-pointer' : '']"
    @click="toggleExpanded"
  >
    <div class="p-4">
      <!-- Header -->
      <div class="flex items-start justify-between gap-3 mb-3">
        <div class="flex-1">
          <p class="font-medium text-gray-800">{{ parameter.name }}</p>
          <p class="text-xs text-gray-500 mt-0.5">
            {{ t('analysis.normal_range') }}: {{ parameter.normal_range || '-' }}
          </p>
        </div>
        
        <!-- Status Badge -->
        <span
          :class="[
            'flex-shrink-0 inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium',
            statusConfig.bgClass,
            statusConfig.textClass,
            statusConfig.pulse ? 'animate-pulse' : ''
          ]"
        >
          <span v-if="statusConfig.icon">{{ statusConfig.icon }}</span>
          {{ statusConfig.label }}
        </span>
      </div>
      
      <!-- Value -->
      <div class="flex items-baseline gap-2">
        <span class="text-2xl font-bold text-gray-900">{{ parameter.value }}</span>
        <span v-if="parameter.unit" class="text-gray-500">{{ parameter.unit }}</span>
      </div>
      
      <!-- Expand Indicator -->
      <div v-if="hasExplanation" class="flex items-center justify-center mt-3 pt-3 border-t border-gray-100">
        <span class="text-xs text-gray-500">{{ isExpanded ? t('analysis.show_less') : t('analysis.plain_explanation') }}</span>
        <svg
          :class="['w-4 h-4 text-gray-400 ml-1 transition-transform', isExpanded ? 'rotate-180' : '']"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
      </div>
    </div>
    
    <!-- Expanded Explanation -->
    <Transition name="slide">
      <div v-if="isExpanded && hasExplanation" class="bg-blue-50 px-4 py-3 border-t border-blue-100">
        <div class="flex items-start gap-2">
          <span class="text-blue-500">💡</span>
          <p class="text-sm text-blue-800">{{ parameter.plain_explanation }}</p>
        </div>
      </div>
    </Transition>
  </div>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
