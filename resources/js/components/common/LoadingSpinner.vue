<script setup>
const props = defineProps({
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  text: {
    type: String,
    default: ''
  }
})

const sizeClasses = {
  sm: 'w-5 h-5',
  md: 'w-8 h-8',
  lg: 'w-12 h-12'
}
</script>

<template>
  <div class="flex flex-col items-center justify-center gap-3">
    <div class="relative">
      <div 
        :class="[sizeClasses[props.size], 'border-4 border-teal-200 border-t-teal-600 rounded-full animate-spin']"
      ></div>
    </div>
    <p v-if="props.text" class="text-gray-600 text-sm animate-pulse">{{ props.text }}</p>
  </div>
</template>
