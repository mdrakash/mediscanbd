<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRoute, useRouter } from 'vue-router'
import { useAnalysisStore } from '../stores/analysis'
import { useLanguageStore } from '../stores/language'
import { useToast } from '../composables/useToast'
import TypeSelector from '../components/upload/TypeSelector.vue'
import FileDropzone from '../components/upload/FileDropzone.vue'
import AnalyzingOverlay from '../components/common/AnalyzingOverlay.vue'

const { t, locale } = useI18n()
const route = useRoute()
const router = useRouter()
const analysisStore = useAnalysisStore()
const languageStore = useLanguageStore()
const toast = useToast()

// State
const documentType = ref('')
const selectedFile = ref(null)
const analysisLanguage = ref(locale.value || 'bn')
const isSubmitting = ref(false)

// Computed
const canSubmit = computed(() => {
  return documentType.value && selectedFile.value && !isSubmitting.value
})

// Methods
function setAnalysisLanguage(lang) {
  analysisLanguage.value = lang
}

async function handleSubmit() {
  // Validation
  if (!documentType.value) {
    toast.error(t('errors.select_type_first'))
    return
  }

  if (!selectedFile.value) {
    toast.error(t('errors.select_file_first'))
    return
  }

  try {
    isSubmitting.value = true

    // Create FormData
    const formData = new FormData()
    formData.append('file', selectedFile.value)
    formData.append('type', documentType.value)
    formData.append('language', analysisLanguage.value)

    // Call analyze API through store
    const result = await analysisStore.analyze(formData)

    // Store handles redirection to /analysis/:id
    // But if it doesn't, we redirect manually
    if (result?.id) {
      router.push({ name: 'AnalysisResult', params: { id: result.id } })
    }
  } catch (error) {
    console.error('Analysis failed:', error)
    // Toast is already shown by the store
  } finally {
    isSubmitting.value = false
  }
}

// Initialize from query params
onMounted(() => {
  const typeParam = route.query.type
  if (typeParam === 'prescription' || typeParam === 'test_report') {
    documentType.value = typeParam
  }
})

// Watch for route query changes
watch(() => route.query.type, (newType) => {
  if (newType === 'prescription' || newType === 'test_report') {
    documentType.value = newType
  }
})
</script>

<template>
  <div class="max-w-2xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
      <h1 class="text-2xl font-bold text-gray-800 mb-2">
        {{ t('upload.title') }}
      </h1>
      <p class="text-gray-600">
        {{ t('upload.subtitle') }}
      </p>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
      <!-- Step 1: Type Selection -->
      <div>
        <div class="flex items-center gap-2 mb-4">
          <span class="w-6 h-6 rounded-full bg-teal-500 text-white text-sm font-bold flex items-center justify-center">1</span>
          <span class="text-sm font-medium text-gray-700">{{ t('upload.select_type') }}</span>
        </div>
        <TypeSelector v-model="documentType" />
      </div>

      <!-- Divider -->
      <hr class="border-gray-200" />

      <!-- Step 2: File Upload -->
      <div>
        <div class="flex items-center gap-2 mb-4">
          <span class="w-6 h-6 rounded-full bg-teal-500 text-white text-sm font-bold flex items-center justify-center">2</span>
          <span class="text-sm font-medium text-gray-700">{{ t('upload.drag_drop') }}</span>
        </div>
        <FileDropzone
          v-model="selectedFile"
          accept="image/jpeg,image/png,image/webp"
          :maxSize="10 * 1024 * 1024"
        />
      </div>

      <!-- Divider -->
      <hr class="border-gray-200" />

      <!-- Step 3: Language Selection -->
      <div>
        <div class="flex items-center gap-2 mb-4">
          <span class="w-6 h-6 rounded-full bg-teal-500 text-white text-sm font-bold flex items-center justify-center">3</span>
          <span class="text-sm font-medium text-gray-700">{{ t('upload.select_language') }}</span>
        </div>

        <!-- Language Toggle -->
        <div class="flex items-center gap-4">
          <div class="flex rounded-lg overflow-hidden border-2 border-gray-200">
            <button
              type="button"
              @click="setAnalysisLanguage('bn')"
              :class="[
                'px-4 py-2 text-sm font-medium transition-colors',
                analysisLanguage === 'bn'
                  ? 'bg-teal-500 text-white'
                  : 'bg-white text-gray-600 hover:bg-gray-50'
              ]"
            >
              বাং
            </button>
            <button
              type="button"
              @click="setAnalysisLanguage('en')"
              :class="[
                'px-4 py-2 text-sm font-medium transition-colors',
                analysisLanguage === 'en'
                  ? 'bg-teal-500 text-white'
                  : 'bg-white text-gray-600 hover:bg-gray-50'
              ]"
            >
              EN
            </button>
          </div>

          <!-- Language Note -->
          <p class="text-sm text-gray-500 italic">
            {{ t('upload.language_note') }}
          </p>
        </div>
      </div>

      <!-- Divider -->
      <hr class="border-gray-200" />

      <!-- Submit Button -->
      <div class="pt-2">
        <button
          type="button"
          @click="handleSubmit"
          :disabled="!canSubmit"
          :class="[
            'w-full py-4 rounded-xl font-semibold text-lg transition-all duration-200',
            'flex items-center justify-center gap-3',
            canSubmit
              ? 'bg-gradient-to-r from-teal-500 to-teal-600 text-white hover:from-teal-600 hover:to-teal-700 shadow-lg hover:shadow-xl'
              : 'bg-gray-200 text-gray-500 cursor-not-allowed'
          ]"
        >
          <template v-if="isSubmitting">
            <!-- Loading Spinner -->
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>{{ t('upload.analyzing') }}</span>
          </template>
          <template v-else>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <span>{{ t('upload.analyze_btn') }}</span>
          </template>
        </button>
      </div>

      <!-- Disclaimer -->
      <p class="text-xs text-gray-400 text-center">
        {{ t('analysis.disclaimer_text') }}
      </p>
    </div>
  </div>

  <!-- Analyzing Overlay -->
  <AnalyzingOverlay :show="isSubmitting" />
</template>
