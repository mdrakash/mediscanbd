<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()
</script>

<template>
  <div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg p-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-6">
        {{ t('upload.title') }}
      </h1>
      <div class="text-center text-gray-500 py-12">
        <p>Upload functionality will be implemented in SESSION 09</p>
      </div>
    </div>
  </div>
</template>
