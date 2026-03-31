<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

// Props & Emits
const props = defineProps({
  modelValue: {
    type: [File, null],
    default: null
  },
  accept: {
    type: String,
    default: 'image/jpeg,image/png,image/webp'
  },
  maxSize: {
    type: Number,
    default: 10 * 1024 * 1024 // 10MB in bytes
  }
})

const emit = defineEmits(['update:modelValue'])

// State
const isDragging = ref(false)
const error = ref('')
const fileInput = ref(null)
const previewUrl = ref(null)

// Computed
const acceptedTypes = computed(() => props.accept.split(',').map(t => t.trim()))

const formattedMaxSize = computed(() => {
  const mb = props.maxSize / (1024 * 1024)
  return `${mb}MB`
})

const formattedFileSize = computed(() => {
  if (!props.modelValue) return ''
  const bytes = props.modelValue.size
  if (bytes < 1024) return `${bytes} B`
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`
  return `${(bytes / (1024 * 1024)).toFixed(2)} MB`
})

// Methods
function validateFile(file) {
  error.value = ''

  // Check file type
  if (!acceptedTypes.value.includes(file.type)) {
    error.value = t('errors.invalid_file')
    return false
  }

  // Check file size
  if (file.size > props.maxSize) {
    error.value = t('errors.file_too_large')
    return false
  }

  return true
}

function handleFile(file) {
  if (!validateFile(file)) {
    return
  }

  // Create preview URL
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
  }
  previewUrl.value = URL.createObjectURL(file)

  emit('update:modelValue', file)
}

function onDragEnter(e) {
  e.preventDefault()
  isDragging.value = true
}

function onDragLeave(e) {
  e.preventDefault()
  isDragging.value = false
}

function onDragOver(e) {
  e.preventDefault()
  isDragging.value = true
}

function onDrop(e) {
  e.preventDefault()
  isDragging.value = false

  const files = e.dataTransfer?.files
  if (files && files.length > 0) {
    handleFile(files[0])
  }
}

function onFileSelect(e) {
  const files = e.target?.files
  if (files && files.length > 0) {
    handleFile(files[0])
  }
}

function openFileDialog() {
  fileInput.value?.click()
}

function removeFile() {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value)
    previewUrl.value = null
  }
  error.value = ''
  emit('update:modelValue', null)
  
  // Reset file input
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}
</script>

<template>
  <div class="space-y-3">
    <!-- Hidden file input -->
    <input
      ref="fileInput"
      type="file"
      :accept="accept"
      class="hidden"
      @change="onFileSelect"
    />

    <!-- Dropzone area -->
    <div
      v-if="!modelValue"
      @click="openFileDialog"
      @dragenter="onDragEnter"
      @dragleave="onDragLeave"
      @dragover="onDragOver"
      @drop="onDrop"
      :class="[
        'relative rounded-xl border-2 border-dashed p-8 transition-all duration-200 cursor-pointer',
        'flex flex-col items-center justify-center min-h-[240px]',
        isDragging
          ? 'border-teal-500 bg-teal-50 scale-[1.01]'
          : error
            ? 'border-red-300 bg-red-50 hover:border-red-400'
            : 'border-teal-300 bg-gray-50 hover:border-teal-400 hover:bg-teal-50/50'
      ]"
    >
      <!-- Upload Icon -->
      <div
        :class="[
          'w-16 h-16 rounded-full flex items-center justify-center mb-4 transition-all duration-200',
          isDragging
            ? 'bg-teal-500 text-white scale-110'
            : error
              ? 'bg-red-100 text-red-500'
              : 'bg-teal-100 text-teal-500'
        ]"
      >
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
          />
        </svg>
      </div>

      <!-- Text -->
      <p
        :class="[
          'text-lg font-medium mb-1',
          isDragging ? 'text-teal-700' : error ? 'text-red-600' : 'text-gray-700'
        ]"
      >
        {{ t('upload.drag_drop') }}
      </p>
      <p class="text-sm text-gray-500 mb-3">
        {{ t('upload.or_click') }}
      </p>

      <!-- Supported formats -->
      <p class="text-xs text-gray-400">
        {{ t('upload.supported') }}
      </p>

      <!-- Error message -->
      <p v-if="error" class="mt-3 text-sm text-red-600 font-medium">
        {{ error }}
      </p>
    </div>

    <!-- File Preview -->
    <div
      v-else
      class="relative rounded-xl border-2 border-teal-400 bg-teal-50 p-4 animate-fadeIn"
    >
      <div class="flex items-start gap-4">
        <!-- Image Thumbnail -->
        <div class="relative flex-shrink-0">
          <img
            :src="previewUrl"
            :alt="modelValue.name"
            class="w-24 h-24 object-cover rounded-lg shadow-md"
          />
          <div class="absolute -top-2 -right-2 w-6 h-6 bg-teal-500 rounded-full flex items-center justify-center">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
          </div>
        </div>

        <!-- File Info -->
        <div class="flex-1 min-w-0">
          <p class="text-sm font-medium text-gray-800 truncate">
            {{ modelValue.name }}
          </p>
          <p class="text-xs text-gray-500 mt-1">
            {{ t('upload.file_size') }}: {{ formattedFileSize }}
          </p>
          
          <!-- Remove Button -->
          <button
            type="button"
            @click="removeFile"
            class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 text-sm text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            {{ t('upload.remove_file') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}
</style>
