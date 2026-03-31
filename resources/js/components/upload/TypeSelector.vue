<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// Props & Emits
const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

// Methods
function selectType(type) {
  emit('update:modelValue', type)
}

function isSelected(type) {
  return props.modelValue === type
}
</script>

<template>
  <div class="space-y-3">
    <label class="block text-sm font-medium text-gray-700">
      {{ t('upload.select_type') }}
    </label>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <!-- Prescription Card -->
      <button
        type="button"
        @click="selectType('prescription')"
        :class="[
          'relative p-6 rounded-xl border-2 transition-all duration-200 text-left group',
          'hover:scale-[1.02] hover:shadow-md',
          isSelected('prescription')
            ? 'border-teal-500 bg-teal-50 shadow-md'
            : 'border-gray-200 bg-white hover:border-teal-300'
        ]"
      >
        <!-- Selected Indicator -->
        <div
          v-if="isSelected('prescription')"
          class="absolute top-3 right-3 w-6 h-6 bg-teal-500 rounded-full flex items-center justify-center"
        >
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>

        <!-- Icon -->
        <div
          :class="[
            'w-14 h-14 rounded-xl flex items-center justify-center mb-4 transition-colors',
            isSelected('prescription')
              ? 'bg-teal-500 text-white'
              : 'bg-teal-100 text-teal-600 group-hover:bg-teal-200'
          ]"
        >
          <!-- Pill/Medicine Icon -->
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="1.5" 
              d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3" 
            />
          </svg>
        </div>

        <!-- Text -->
        <h3
          :class="[
            'text-lg font-semibold mb-1 transition-colors',
            isSelected('prescription') ? 'text-teal-700' : 'text-gray-800'
          ]"
        >
          {{ t('upload.prescription') }}
        </h3>
        <p class="text-sm text-gray-500">
          {{ t('upload.prescription_desc') }}
        </p>
      </button>

      <!-- Test Report Card -->
      <button
        type="button"
        @click="selectType('test_report')"
        :class="[
          'relative p-6 rounded-xl border-2 transition-all duration-200 text-left group',
          'hover:scale-[1.02] hover:shadow-md',
          isSelected('test_report')
            ? 'border-teal-500 bg-teal-50 shadow-md'
            : 'border-gray-200 bg-white hover:border-teal-300'
        ]"
      >
        <!-- Selected Indicator -->
        <div
          v-if="isSelected('test_report')"
          class="absolute top-3 right-3 w-6 h-6 bg-teal-500 rounded-full flex items-center justify-center"
        >
          <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>

        <!-- Icon -->
        <div
          :class="[
            'w-14 h-14 rounded-xl flex items-center justify-center mb-4 transition-colors',
            isSelected('test_report')
              ? 'bg-teal-500 text-white'
              : 'bg-amber-100 text-amber-600 group-hover:bg-amber-200'
          ]"
        >
          <!-- Test Tube / Report Icon -->
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path 
              stroke-linecap="round" 
              stroke-linejoin="round" 
              stroke-width="1.5" 
              d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21a48.309 48.309 0 01-8.135-.687c-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" 
            />
          </svg>
        </div>

        <!-- Text -->
        <h3
          :class="[
            'text-lg font-semibold mb-1 transition-colors',
            isSelected('test_report') ? 'text-teal-700' : 'text-gray-800'
          ]"
        >
          {{ t('upload.test_report') }}
        </h3>
        <p class="text-sm text-gray-500">
          {{ t('upload.test_report_desc') }}
        </p>
      </button>
    </div>
  </div>
</template>
