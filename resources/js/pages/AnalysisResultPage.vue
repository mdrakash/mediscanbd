<script setup>
import { ref, computed, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRoute, useRouter } from 'vue-router'
import { useAnalysisStore } from '../stores/analysis'
import { useToast } from '../composables/useToast'
import { useRelativeTime } from '../composables/useRelativeTime'

// Components
import LoadingSpinner from '../components/common/LoadingSpinner.vue'
import MedicineCard from '../components/analysis/MedicineCard.vue'
import ParameterRow from '../components/analysis/ParameterRow.vue'
import OverallStatusBanner from '../components/analysis/OverallStatusBanner.vue'
import DisclaimerBanner from '../components/analysis/DisclaimerBanner.vue'
import FoodAdvice from '../components/analysis/FoodAdvice.vue'
import CollapsibleSection from '../components/analysis/CollapsibleSection.vue'

const { t } = useI18n()
const route = useRoute()
const router = useRouter()
const analysisStore = useAnalysisStore()
const toast = useToast()
const { formatDateTime } = useRelativeTime()

// State
const isLoading = ref(true)
const error = ref(null)

// Computed
const analysis = computed(() => analysisStore.currentAnalysis)

const isPrescription = computed(() => {
  return analysis.value?.upload?.type === 'prescription'
})

const isTestReport = computed(() => {
  return analysis.value?.upload?.type === 'test_report'
})

const documentName = computed(() => {
  if (!analysis.value) return ''
  return analysis.value.upload?.original_filename || 
    (isPrescription.value ? t('upload.prescription') : t('upload.test_report'))
})

const hasMedicines = computed(() => {
  return analysis.value?.medicines && analysis.value.medicines.length > 0
})

const hasParameters = computed(() => {
  return analysis.value?.parameters && analysis.value.parameters.length > 0
})

const hasTestsAdvised = computed(() => {
  return analysis.value?.tests_advised && analysis.value.tests_advised.length > 0
})

const hasAbnormalFindings = computed(() => {
  return analysis.value?.abnormal_findings && analysis.value.abnormal_findings.length > 0
})

const hasFoodAdvice = computed(() => {
  const toEat = analysis.value?.foods_to_eat
  const toAvoid = analysis.value?.foods_to_avoid
  return (toEat && toEat.length > 0) || (toAvoid && toAvoid.length > 0)
})

// Methods
async function loadAnalysis() {
  const id = route.params.id
  
  if (!id) {
    error.value = t('errors.analysis_not_found')
    isLoading.value = false
    return
  }

  try {
    isLoading.value = true
    error.value = null
    await analysisStore.fetchAnalysis(id)
  } catch (err) {
    error.value = err.response?.data?.message || t('errors.load_failed')
  } finally {
    isLoading.value = false
  }
}

function goBack() {
  router.back()
}

function goToNewAnalysis() {
  router.push({ name: 'Upload' })
}

async function handleShare() {
  const url = window.location.href
  
  // Try Web Share API first
  if (navigator.share) {
    try {
      await navigator.share({
        title: t('app.name') + ' - ' + t('analysis.result_title'),
        text: documentName.value,
        url: url
      })
      return
    } catch (err) {
      // User cancelled or error
      if (err.name !== 'AbortError') {
        console.error('Share failed:', err)
      }
    }
  }
  
  // Fallback to clipboard
  try {
    await navigator.clipboard.writeText(url)
    toast.success(t('analysis.copied_to_clipboard'))
  } catch (err) {
    toast.error(t('analysis.share_failed'))
  }
}

function handlePrint() {
  window.print()
}

// Lifecycle
onMounted(() => {
  loadAnalysis()
})
</script>

<template>
  <div class="max-w-4xl mx-auto">
    <!-- Loading State -->
    <div v-if="isLoading" class="flex flex-col items-center justify-center py-20">
      <LoadingSpinner size="lg" />
      <p class="mt-4 text-gray-500">{{ t('common.loading') }}</p>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="bg-white rounded-2xl shadow-lg p-8 text-center">
      <div class="w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>
      </div>
      <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ t('common.error') }}</h2>
      <p class="text-gray-600 mb-6">{{ error }}</p>
      <div class="flex justify-center gap-3">
        <button
          @click="goBack"
          class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors"
        >
          {{ t('common.back') }}
        </button>
        <button
          @click="loadAnalysis"
          class="px-4 py-2 bg-teal-500 text-white rounded-lg hover:bg-teal-600 transition-colors"
        >
          {{ t('common.retry') }}
        </button>
      </div>
    </div>

    <!-- Analysis Result -->
    <div v-else-if="analysis" class="space-y-4 print:space-y-6">
      <!-- Sticky Top Bar -->
      <div class="sticky top-0 z-10 bg-white rounded-xl shadow-md p-3 sm:p-4 flex items-center justify-between gap-3 print:hidden">
        <!-- Back Button -->
        <button
          @click="goBack"
          class="flex items-center gap-1 text-gray-600 hover:text-gray-800 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
          <span class="hidden sm:inline">{{ t('common.back') }}</span>
        </button>

        <!-- Document Name -->
        <div class="flex-1 min-w-0 text-center">
          <p class="text-sm font-medium text-gray-800 truncate">{{ documentName }}</p>
          <p class="text-xs text-gray-500">{{ formatDateTime(analysis.created_at) }}</p>
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-2">
          <button
            @click="handleShare"
            class="p-2 text-gray-500 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors"
            :title="t('analysis.share')"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
            </svg>
          </button>
          <button
            @click="handlePrint"
            class="p-2 text-gray-500 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors"
            :title="t('analysis.print')"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
          </button>
          <button
            @click="goToNewAnalysis"
            class="hidden sm:flex items-center gap-1.5 px-3 py-2 bg-teal-500 text-white text-sm rounded-lg hover:bg-teal-600 transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            {{ t('analysis.new_analysis') }}
          </button>
        </div>
      </div>

      <!-- ==================== PRESCRIPTION RESULT ==================== -->
      <template v-if="isPrescription">
        <!-- Medicines Section -->
        <CollapsibleSection
          v-if="hasMedicines"
          :title="t('analysis.medicines')"
          icon="💊"
        >
          <p class="text-sm text-gray-500 mb-4">
            {{ t('analysis.medicine_count', { count: analysis.medicines.length }) }}
          </p>
          <div class="space-y-4">
            <MedicineCard
              v-for="(medicine, index) in analysis.medicines"
              :key="index"
              :medicine="medicine"
              :index="index + 1"
            />
          </div>
        </CollapsibleSection>

        <!-- Tests Advised -->
        <CollapsibleSection
          v-if="hasTestsAdvised"
          :title="t('analysis.tests_advised')"
          icon="🔬"
        >
          <ul class="space-y-2">
            <li
              v-for="(test, index) in analysis.tests_advised"
              :key="index"
              class="flex items-start gap-2 text-gray-700"
            >
              <span class="w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xs font-medium flex-shrink-0">
                {{ index + 1 }}
              </span>
              <span>{{ test }}</span>
            </li>
          </ul>
        </CollapsibleSection>

        <!-- Diagnosis -->
        <CollapsibleSection
          v-if="analysis.diagnosis"
          :title="t('analysis.diagnosis')"
          icon="🩺"
        >
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ analysis.diagnosis }}</p>
        </CollapsibleSection>

        <!-- General Advice -->
        <CollapsibleSection
          v-if="analysis.general_advice"
          :title="t('analysis.general_advice')"
          icon="💡"
        >
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ analysis.general_advice }}</p>
        </CollapsibleSection>

        <!-- Next Steps -->
        <CollapsibleSection
          v-if="analysis.next_steps"
          :title="t('analysis.next_steps')"
          icon="📋"
        >
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ analysis.next_steps }}</p>
        </CollapsibleSection>

        <!-- Lifestyle Tips -->
        <CollapsibleSection
          v-if="analysis.lifestyle_tips"
          :title="t('analysis.lifestyle_tips')"
          icon="🏃"
        >
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ analysis.lifestyle_tips }}</p>
        </CollapsibleSection>
      </template>

      <!-- ==================== TEST REPORT RESULT ==================== -->
      <template v-if="isTestReport">
        <!-- Overall Status Banner -->
        <OverallStatusBanner
          v-if="analysis.overall_status"
          :status="analysis.overall_status"
        />

        <!-- Report Type & Summary -->
        <CollapsibleSection
          :title="t('analysis.test_summary')"
          icon="📊"
        >
          <div v-if="analysis.report_type" class="mb-3">
            <span class="text-sm text-gray-500">{{ t('analysis.report_type') }}:</span>
            <span class="ml-2 px-2 py-1 bg-teal-100 text-teal-700 rounded text-sm font-medium">
              {{ analysis.report_type }}
            </span>
          </div>
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ analysis.test_summary }}</p>
        </CollapsibleSection>

        <!-- Parameters Table -->
        <CollapsibleSection
          v-if="hasParameters"
          :title="t('analysis.parameters')"
          icon="📈"
        >
          <!-- Desktop Table -->
          <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200">
                  <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ t('analysis.parameter_name') }}</th>
                  <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ t('analysis.parameter_value') }}</th>
                  <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ t('analysis.normal_range') }}</th>
                  <th class="text-left py-3 px-4 text-sm font-medium text-gray-600">{{ t('common.status') }}</th>
                </tr>
              </thead>
              <tbody>
                <ParameterRow
                  v-for="(param, index) in analysis.parameters"
                  :key="index"
                  :parameter="param"
                />
              </tbody>
            </table>
          </div>

          <!-- Mobile Cards -->
          <div class="sm:hidden space-y-3">
            <ParameterRow
              v-for="(param, index) in analysis.parameters"
              :key="index"
              :parameter="param"
            />
          </div>
        </CollapsibleSection>

         <!-- Abnormal Findings -->
         <div
           v-if="hasAbnormalFindings"
           class="bg-red-50 rounded-xl border border-red-200 p-4 sm:p-5"
         >
           <div class="flex items-start gap-2 mb-3">
             <span class="text-xl flex-shrink-0 mt-0.5">⚠️</span>
             <h3 class="font-semibold text-red-800 text-base sm:text-lg">{{ t('analysis.abnormal_findings') }}</h3>
           </div>
           <ul class="space-y-3 pl-2">
             <li
               v-for="(finding, index) in analysis.abnormal_findings"
               :key="index"
               class="flex items-start gap-3 text-red-700 text-sm sm:text-base"
             >
               <span class="w-1.5 h-1.5 rounded-full bg-red-500 mt-2 flex-shrink-0"></span>
               <span class="flex-1 whitespace-normal">{{ finding }}</span>
             </li>
           </ul>
         </div>

        <!-- Possible Implications -->
        <CollapsibleSection
          v-if="analysis.possible_implications"
          :title="t('analysis.possible_implications')"
          icon="🔍"
        >
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ analysis.possible_implications }}</p>
        </CollapsibleSection>

        <!-- Recommended Actions -->
        <CollapsibleSection
          v-if="analysis.recommended_actions"
          :title="t('analysis.recommended_actions')"
          icon="✅"
        >
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ analysis.recommended_actions }}</p>
        </CollapsibleSection>

        <!-- Food Advice -->
        <CollapsibleSection
          v-if="hasFoodAdvice"
          :title="t('analysis.food_advice')"
          icon="🍎"
        >
          <FoodAdvice
            :to-eat="analysis.foods_to_eat"
            :to-avoid="analysis.foods_to_avoid"
          />
        </CollapsibleSection>

        <!-- Next Steps -->
        <CollapsibleSection
          v-if="analysis.next_steps"
          :title="t('analysis.next_steps')"
          icon="📋"
        >
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ analysis.next_steps }}</p>
        </CollapsibleSection>

        <!-- When to See Doctor -->
        <CollapsibleSection
          v-if="analysis.when_to_see_doctor"
          :title="t('analysis.when_doctor')"
          icon="👨‍⚕️"
        >
          <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ analysis.when_to_see_doctor }}</p>
        </CollapsibleSection>
      </template>

      <!-- Disclaimer (Both types) -->
      <DisclaimerBanner :text="analysis.disclaimer" />

      <!-- Mobile New Analysis Button -->
      <div class="sm:hidden print:hidden">
        <button
          @click="goToNewAnalysis"
          class="w-full flex items-center justify-center gap-2 py-3 bg-teal-500 text-white rounded-xl hover:bg-teal-600 transition-colors"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          {{ t('analysis.new_analysis') }}
        </button>
      </div>
    </div>
  </div>
</template>

<style>
/* Print Styles */
@media print {
  body {
    background: white !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }
  
  .print\:hidden {
    display: none !important;
  }
  
  .print\:space-y-6 > * + * {
    margin-top: 1.5rem !important;
  }
  
  /* Ensure all sections are visible when printing */
  [v-show], .collapse-enter-active, .collapse-leave-active {
    display: block !important;
    max-height: none !important;
    opacity: 1 !important;
  }
  
  /* Page break controls */
  .bg-white.rounded-xl {
    break-inside: avoid;
    page-break-inside: avoid;
  }
  
  /* Remove shadows and transitions for print */
  * {
    box-shadow: none !important;
    transition: none !important;
  }
}
</style>
