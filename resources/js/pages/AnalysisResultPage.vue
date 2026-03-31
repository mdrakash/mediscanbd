<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
  <div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">
        {{ t('analysis.medicines') }}
      </h1>
      <div class="text-center text-gray-500 py-12">
        <p>Analysis result page will be implemented in SESSION 10</p>
      </div>
    </div>
  </div>
</template>
