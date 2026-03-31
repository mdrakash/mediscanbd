<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// Props
const props = defineProps({
  status: {
    type: String,
    required: true,
    validator: (value) => ['normal', 'mildly_abnormal', 'moderately_abnormal', 'severely_abnormal'].includes(value)
  }
})

// Computed
const statusConfig = computed(() => {
  const configs = {
    normal: {
      label: t('analysis.status_normal'),
      bgClass: 'bg-gradient-to-r from-green-500 to-green-600',
      iconBgClass: 'bg-green-400',
      icon: 'check'
    },
    mildly_abnormal: {
      label: t('analysis.status_mildly_abnormal'),
      bgClass: 'bg-gradient-to-r from-yellow-500 to-yellow-600',
      iconBgClass: 'bg-yellow-400',
      icon: 'info'
    },
    moderately_abnormal: {
      label: t('analysis.status_moderately_abnormal'),
      bgClass: 'bg-gradient-to-r from-orange-500 to-orange-600',
      iconBgClass: 'bg-orange-400',
      icon: 'warning'
    },
    severely_abnormal: {
      label: t('analysis.status_severely_abnormal'),
      bgClass: 'bg-gradient-to-r from-red-500 to-red-600',
      iconBgClass: 'bg-red-400',
      icon: 'alert',
      pulse: true
    }
  }
  
  return configs[props.status] || configs.normal
})
</script>

<template>
  <div
    :class="[
      'rounded-xl p-4 sm:p-5 text-white shadow-lg',
      statusConfig.bgClass,
      statusConfig.pulse ? 'animate-pulse-slow' : ''
    ]"
  >
    <div class="flex items-center gap-4">
      <!-- Icon -->
      <div
        :class="[
          'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center',
          statusConfig.iconBgClass
        ]"
      >
        <!-- Check Icon (Normal) -->
        <svg v-if="statusConfig.icon === 'check'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
        </svg>
        
        <!-- Info Icon (Mildly Abnormal) -->
        <svg v-else-if="statusConfig.icon === 'info'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        
        <!-- Warning Icon (Moderately Abnormal) -->
        <svg v-else-if="statusConfig.icon === 'warning'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
        
        <!-- Alert Icon (Severely Abnormal) -->
        <svg v-else-if="statusConfig.icon === 'alert'" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
      </div>
      
      <!-- Text -->
      <div class="flex-1">
        <p class="text-white/80 text-xs font-medium uppercase tracking-wide mb-1">
          {{ t('analysis.overall_status') }}
        </p>
        <p class="text-lg sm:text-xl font-bold leading-tight">
          {{ statusConfig.label }}
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes pulse-slow {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.85;
  }
}

.animate-pulse-slow {
  animation: pulse-slow 2s ease-in-out infinite;
}
</style>
