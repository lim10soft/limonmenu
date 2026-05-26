<template>
  <div class="flex flex-col items-center justify-center min-h-screen gap-4 p-8 text-center">
    <span class="text-6xl">🍽️</span>
    <h1 class="text-2xl font-bold text-gray-800">{{ t('menu.notFound') }}</h1>
    <p class="text-gray-500">{{ t('menu.invalidQr') }}</p>
  </div>
</template>

<script setup lang="ts">
import { useI18n } from 'vue-i18n'
const { t } = useI18n()
</script>
